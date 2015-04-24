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
        LogLevel::EMERGENCY => 80,
        LogLevel::ALERT => 70,
        LogLevel::CRITICAL => 60,
        LogLevel::ERROR => 50,
        LogLevel::WARNING => 40,
        LogLevel::NOTICE => 30,
        LogLevel::INFO => 20,
        LogLevel::DEBUG => 10
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
     * @throws InvalidArgumentException
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
     * @return void
     * @throws InvalidArgumentException
     */
    public function log($level, $message, array $context = array())
    {
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

            fwrite($this->handle, $message);
        }
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
            ['{date}', '{level}', '{message}'],
            ['%1$s', '%2$s', '%3$s'],
            $format
        );
    }

    /**
     * Sets the threshold level of the log instance.
     *
     * @param integer $level the new level to set
     * @return void
     * @throws InvalidArgumentException
     */
    public function setLogThreshold($level)
    {
        if (!isset($this->levelSeverity[$level])) {
            throw new InvalidArgumentException('invalid log level ' . $level);
        }

        $this->thresholdLevel = $this->levelSeverity[$level];
    }

    /**
     * Gets the current threshold level of the log instance.
     *
     * @return string
     */
    public function getLogThreshold()
    {
        return array_search($this->thresholdLevel, $this->levelSeverity);
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
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{' . $key . '}'] = $value;
        }

        return strtr($message, $replace);
    }
}
