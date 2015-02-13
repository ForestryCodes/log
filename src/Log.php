<?php
namespace Forestry\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;

/**
 * Class Log
 *
 * @package Forestry\Log
 */
class Log extends AbstractLogger
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var resource
     */
    private $handle;

    /**
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    private $logFormat = '%1$s %2$s %3$s';

	/**
	 * Severity values for each log level.
	 *
	 * @var array
	 */
	private $levelSeverity = [
		LogLevel::EMERGENCY	=> 80,
		LogLevel::ALERT		=> 70,
		LogLevel::CRITICAL	=> 60,
		LogLevel::ERROR		=> 50,
		LogLevel::WARNING	=> 40,
		LogLevel::NOTICE	=> 30,
		LogLevel::INFO		=> 20,
		LogLevel::DEBUG		=> 10
	];

	/**
	 * Threshold value of the current Log instance.
	 *
	 * @var integer
	 */
	private $thresholdLevel;

	/**
	 * Constructor method.
	 *
	 * @param string $folder
	 * @param string $fileName
	 * @param string $threshold
	 * @throws \RuntimeException
	 * @thorws InvalidArgumentException
	 */
	public function __construct($folder, $fileName, $threshold = LogLevel::DEBUG)
	{
		if (!is_dir($folder) || !is_writable($folder)) {
			throw new \RuntimeException('Folder does not exist, or is not writable');
		}

		if (!isset($this->levelSeverity[$threshold])) {
			throw new InvalidArgumentException('invalid log level ' . $threshold);
		}

		$this->thresholdLevel = $this->levelSeverity[$threshold];
		$this->filePath = $folder . DIRECTORY_SEPARATOR . $fileName;
		$this->handle = fopen($this->filePath, 'a');

		if (!$this->handle) {
			throw new \RuntimeException('Error opening log file with path: ' . $this->filePath);
		}
	}

	/**
	 * Closes the handle.
	 */
	public function __destruct()
	{
		if ($this->handle) {
			fclose($this->handle);
		}
	}

	/**
	 * Writes a log message of a given level.
	 *
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 * @throws InvalidArgumentException
	 */
	public function log($level, $message, array $context = array())
	{
		$return = true;

		if (!isset($this->levelSeverity[$level])) {
			throw new InvalidArgumentException('invalid log level ' . $level);
		}

		if ($this->thresholdLevel <= $this->levelSeverity[$level]) {
			$message = sprintf(
				$this->logFormat . PHP_EOL,
				date($this->dateFormat),
				strtoupper($level),
				$this->interpolate((string)$message, $context)
			);

			if (false === fwrite($this->handle, $message)) {
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
	public function emergency($message, array $context = array())
	{
		return $this->log($message, self::EMERGENCY, $context);
	}

	/**
	 * Writes an alert level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function alert($message, array $context = array())
	{
		return $this->log($message, self::ALERT, $context);
	}

	/**
	 * Writes a critical level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function critical($message, array $context = array())
	{
		return $this->log($message, self::CRITICAL, $context);
	}

	/**
	 * Writes an error level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function error($message, array $context = array())
	{
		return $this->log($message, self::ERROR, $context);
	}

	/**
	 * Writes a warning level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function warning($message, array $context = array())
	{
		return $this->log($message, self::WARNING, $context);
	}

	/**
	 * Writes a notice level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function notice($message, array $context = array())
	{
		return $this->log($message, self::NOTICE, $context);
	}

	/**
	 * Writes an info level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function info($message, array $context = array())
	{
		return $this->log($message, self::INFO, $context);
	}

	/**
	 * Writes a debug level message into the log.
	 *
	 * @param string $message
	 * @param array $context
	 * @return boolean
	 */
	public function debug($message, array $context = array())
	{
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
	public function setDateFormat($format)
	{
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
	public function setLogFormat($format)
	{
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
	public function setLevel($level)
	{
		$this->level = (int)$level;
	}

	/**
	 * Gets the current level of the log instance
 	 *
	 * @return int
	 */
	public function getLevel()
	{
		return (int)$this->level;
	}

	/**
	 * Replaces placeholders in the message with the values from the context array.
	 *
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	private function interpolate($message, array $context = array())
	{
		$replace = array();
		foreach ($context as $key => $value) {
			$replace['{' . $key . '}'] = $value;
		}

		return strtr($message, $replace);
	}

}
