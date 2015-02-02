<?php

namespace Forestry\Log;

/**
 * Class Log
 *
 * @package Forestry\Log
 */
class Log {

	/**
	 * @var array
	 */
	private static $levels = array(
		self::EMERGENCY => 'EMERGENCY',
		self::ALERT => 'ALERT',
		self::CRITICAL => 'CRITICAL',
		self::ERROR => 'ERROR',
		self::WARNING => 'WARNING',
		self::NOTICE => 'NOTICE',
		self::INFO => 'INFO',
		self::DEBUG => 'DEBUG'
	);

	/**
	 * @var string
	 */
	private $filePath;

	/**
	 * @var resource
	 */
	private $handle;

	/**
	 * @var integer
	 */
	private $level;

	/**
	 * @var string
	 */
	private $dateFormat = 'Y-m-d H:i:s';

	/**
	 * @var string
	 */
	private $logFormat = '%1$s %2$s %3$s';

	/**
	 * Log levels.
	 */
	const EMERGENCY = 0;
	const ALERT = 1;
	const CRITICAL = 2;
	const ERROR = 3;
	const WARNING = 4;
	const NOTICE = 5;
	const INFO = 6;
	const DEBUG = 7;

	/**
	 * Constructor method.
	 *
	 * @param string $folder
	 * @param string $fileName
	 * @param integer $level
	 * @throws \RuntimeException
	 */
	public function __construct($folder, $fileName, $level = self::DEBUG) {
		if(!is_dir($folder) || !is_writable($folder)) {
			throw new \RuntimeException('Folder does not exist, or is not writable');
		}

		$this->level = (int)$level;
		$this->filePath = $folder . DIRECTORY_SEPARATOR . $fileName;
		$this->handle = fopen($this->filePath, 'a');

		if(!$this->handle) {
			throw new \RuntimeException('Error opening log file with path: ' . $this->filePath);
		}
	}

	/**
	 * Closes the handle.
	 */
	public function __destruct() {
		if($this->handle) {
			fclose($this->handle);
		}
	}

	/**
	 * Writes a log message of a given level.
	 *
	 * @param string $message
	 * @param integer $level
	 * @param array $context
	 * @return boolean
	 * @throws \OutOfBoundsException
	 */
	public function log($message, $level, array $context = array()) {
		$return = true;

		if(!isset(self::$levels[$level])) {
			throw new \OutOfBoundsException('Log level invalid');
		}

		if((int)$level <= $this->level) {
			$message = sprintf(
				$this->logFormat . PHP_EOL,
				date($this->dateFormat),
				self::$levels[$level],
				$this->interpolate((string)$message, $context)
			);

			if(false === fwrite($this->handle, $message)) {
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * Writes an emergency level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function emergency($message, array $context = array()) {
		return $this->log($message, self::EMERGENCY, $context);
	}

	/**
	 * Writes an alert level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function alert($message, array $context = array()) {
		return $this->log($message, self::ALERT, $context);
	}

	/**
	 * Writes a critical level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function critical($message, array $context = array()) {
		return $this->log($message, self::CRITICAL, $context);
	}

	/**
	 * Writes an error level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function error($message, array $context = array()) {
		return $this->log($message, self::ERROR, $context);
	}

	/**
	 * Writes a warning level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function warning($message, array $context = array()) {
		return $this->log($message, self::WARNING, $context);
	}

	/**
	 * Writes a notice level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function notice($message, array $context = array()) {
		return $this->log($message, self::NOTICE, $context);
	}

	/**
	 * Writes an info level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function info($message, array $context = array()) {
		return $this->log($message, self::INFO, $context);
	}

	/**
	 * Writes a debug level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function debug($message, array $context = array()) {
		return $this->log($message, self::DEBUG, $context);
	}

	/**
	 * Sets the date format.
	 *
	 * Accepts any string which is compatible with date().
	 *
	 * @param string $format
	 * @return null
	 */
	public function setDateFormat($format) {
		$this->dateFormat = $format;
	}

	/**
	 * Sets the log string format.
	 *
	 * String will be parsed with sprintf(). Following placeholder are defined:
	 * - {date}
	 * - {level}
	 * - {message}
	 *
	 * Example:
	 * $format = '[{level}|{date}] {message}'; //[INFO|2013-04-25 13:37:42] This is an information message
	 *
	 * @param $format
	 */
	public function setLogFormat($format) {
		$this->logFormat = str_replace(
			array('{date}', '{level}', '{message}'),
			array('%1$s', '%2$s', '%3$s'),
			$format
		);
	}

	/**
	 * Sets the level of the log instance
	 *
	 * @param integer $level the new level to set
	 *
	 * @return void
	 */
	public function setLevel($level) {
		$this->level = (int)$level;
	}

	/**
	 * Gets the current level of the log instance
	 *
	 * @return int
	 */
	public function getLevel() {
		return (int)$this->level;
	}

	/**
	 * Replaces placeholders in the message with the values from the context array.
	 *
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	private function interpolate($message, array $context = array()) {
		$replace = array();
		foreach($context as $key => $value) {
			$replace['{' . $key . '}'] = $value;
		}

		return strtr($message, $replace);
	}

}
