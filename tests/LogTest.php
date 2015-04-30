<?php
namespace Forestry\Log\Test;

use Forestry\Log\Log;
use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;

class LogTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var string
	 */
	private $testFile = 'forestry-log-test.log';

	/**
	 * Clean possibly previous generated test log file.
	 */
	public function setUp()
	{
		if (file_exists('/tmp/' . $this->testFile)) {
			unlink('/tmp/' . $this->testFile);
		}
	}

	public function testCreateInstance()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$this->assertInstanceOf('\Forestry\Log\Log', $log);
		$this->assertFileExists('/tmp/' . $this->testFile);
	}

	public function testLogWithoutContext()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->log(LogLevel::DEBUG, 'A log message');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG A log message/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogWithContext()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->log(LogLevel::DEBUG, 'Hello {name}', array('name' => 'World'));

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG Hello World/', $content);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogEmergency()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->emergency('This is an emergency');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} EMERGENCY This is an emergency/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogAlert()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->alert('This is an alert');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ALERT This is an alert/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogCritical()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->critical('This is a critical situation');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} CRITICAL This is a critical situation/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogError()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->error('This is an error');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ERROR This is an error/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogWarning()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->warning('This is a warning');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} WARNING This is a warning/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogNotice()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->notice('This is just a notice');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} NOTICE This is just a notice/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogInfo()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->info('This is an information');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} INFO This is an information/',
			$content
		);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogDebug()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->debug('This is a debug message');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG This is a debug message/',
			$content
		);
	}

	public function testSetDateFormat()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->setDateFormat('c');
		$log->debug('Set another date format');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2} DEBUG Set another date format/',
			$content
		);
	}

	public function testSetLogFormat()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->setLogFormat('[{date}|{level}] {message}');
		$log->debug('Set another log format');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\|DEBUG\] Set another log format/',
			$content
		);
	}

	public function testSetLogThreshold()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->setLogThreshold(LogLevel::INFO);
		$log->debug('Set log threshold to info');

		$this->assertStringEqualsFile('/tmp/' . $this->testFile, '');
	}

	public function testGetLogThreshold()
	{
		$log = new Log('/tmp/' . $this->testFile);
		$log->setLogThreshold(LogLevel::NOTICE);
		$level = $log->getLogThreshold();

		$this->assertEquals($level, LogLevel::NOTICE);
	}

}
