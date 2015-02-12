<?php
namespace Forestry\Log\Test;

use Forestry\Log\Log;

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

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenFolderDoesNotExist()
	{
		new Log('/whargarble', $this->testFile);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenFolderDoesntHaveWritePermissions()
	{
		new Log('/var', $this->testFile);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenHandleCantBeOpened()
	{
		//Suppressing errors only for testing purpose.
		@new Log('/tmp', '');
	}

	public function testCreateInstance()
	{
		$log = new Log('/tmp', $this->testFile);
		$this->assertInstanceOf('\Forestry\Log\Log', $log);
		$this->assertFileExists('/tmp/' . $this->testFile);
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testThrowsExceptionOnUndefinedLogeLevel()
	{
		$log = new Log('/tmp', $this->testFile);
		$log->log('What level is that?', 16);
	}

	public function testLogWithoutContext()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->log('A log message', Log::DEBUG);
		$this->assertTrue($result);

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
		$log = new Log('/tmp', $this->testFile);
		$result = $log->log('Hello {name}', Log::DEBUG, array('name' => 'World'));
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG Hello World/', $content);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogEmergency()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->emergency('This is an emergency');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} EMERGENCY This is an emergency/',
			$content
		);
	}

	/**
	 * @depends testLogEmergency
	 */
	public function testLogAlert()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->alert('This is an alert');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ALERT This is an alert/',
			$content
		);
	}

	/**
	 * @depends testLogAlert
	 */
	public function testLogCritical()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->critical('This is a critical situation');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} CRITICAL This is a critical situation/',
			$content
		);
	}

	/**
	 * @depends testLogCritical
	 */
	public function testLogError()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->error('This is an error');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ERROR This is an error/',
			$content
		);
	}

	/**
	 * @depends testLogError
	 */
	public function testLogWarning()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->warning('This is a warning');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} WARNING This is a warning/',
			$content
		);
	}

	/**
	 * @depends testLogWarning
	 */
	public function testLogNotice()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->notice('This is just a notice');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} NOTICE This is just a notice/',
			$content
		);
	}

	/**
	 * @depends testLogNotice
	 */
	public function testLogInfo()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->info('This is an information');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} INFO This is an information/',
			$content
		);
	}

	/**
	 * @depends testLogInfo
	 */
	public function testLogDebug()
	{
		$log = new Log('/tmp', $this->testFile);
		$result = $log->debug('This is a debug message');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG This is a debug message/',
			$content
		);
	}

	public function testSetDateFormat()
	{
		$log = new Log('/tmp', $this->testFile);
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
		$log = new Log('/tmp', $this->testFile);
		$log->setLogFormat('[{date}|{level}] {message}');
		$log->debug('Set another log format');

		$content = file_get_contents('/tmp/' . $this->testFile);
		$this->assertRegExp(
			'/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\|DEBUG\] Set another log format/',
			$content
		);
	}

	public function testSetLogLevel()
	{
		$log = new Log('/tmp', $this->testFile);
		$log->setLevel(Log::INFO);
		$log->debug('Set log level to info');

		$this->assertStringEqualsFile('/tmp/' . $this->testFile, '');
	}

	public function testGetLogLevel()
	{
		$log = new Log('/tmp', $this->testFile);
		$log->setLevel(Log::NOTICE);
		$level = $log->getLevel();

		$this->assertEquals($level, 5);
	}

}
