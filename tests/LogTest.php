<?php
require_once __DIR__ . '/../Log.php';


class Log extends PHPUnit_Framework_TestCase {


	public function setUp() {
		if(file_exists('/tmp/test.log')) {
			unlink('/tmp/test.log');
		}
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenFolderDoesNotExist() {
		new Teacup\Log('/whargarble', 'test.log');
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenFolderDoesntHaveWritePermissions() {
		new Teacup\Log('/var', 'test.log');
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testThrowsExceptionWhenHandleCantBeOpened() {
		//Suppressing errors only for testing purpose.
		@new Teacup\Log('/tmp', '');
	}

	public function testCreateInstance() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$this->assertInstanceOf('\Teacup\Log', $log);
		$this->assertFileExists('/tmp/test.log');
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testThrowsExceptionOnUndefinedLogeLevel() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$log->log('What level is that?', 16);
	}

	public function testLogWithoutContext() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->log('A log message', Teacup\Log::DEBUG);
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG A log message/', $content);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogWithtContext() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->log('Hello {name}', Teacup\Log::DEBUG, array('name' => 'World'));
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG Hello World/', $content);
	}

	/**
	 * @depends testLogWithoutContext
	 */
	public function testLogEmergency() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->emergency('This is an emergency');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} EMERGENCY This is an emergency/', $content);
	}

	/**
	 * @depends testLogEmergency
	 */
	public function testLogAlert() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->alert('This is an alert');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ALERT This is an alert/', $content);
	}

	/**
	 * @depends testLogAlert
	 */
	public function testLogCritical() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->critical('This is a critical situation');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} CRITICAL This is a critical situation/',
				$content
		);
	}

	/**
	 * @depends testLogCritical
	 */
	public function testLogError() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->error('This is an error');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ERROR This is an error/', $content);
	}

	/**
	 * @depends testLogError
	 */
	public function testLogWarning() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->warning('This is a warning');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} WARNING This is a warning/', $content);
	}

	/**
	 * @depends testLogWarning
	 */
	public function testLogNotice() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->notice('This is just a notice');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} NOTICE This is just a notice/', $content);
	}

	/**
	 * @depends testLogNotice
	 */
	public function testLogInfo() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->info('This is an information');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} INFO This is an information/', $content);
	}

	/**
	 * @depends testLogInfo
	 */
	public function testLogDebug() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$result = $log->debug('This is a debug message');
		$this->assertTrue($result);

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} DEBUG This is a debug message/', $content);
	}

	public function testSetDateFormat() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$log->setDateFormat('c');
		$log->debug('Setted another date format');

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
				'/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2} DEBUG Setted another date format/',
				$content
		);
	}

	public function testSetLogFormat() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$log->setLogFormat('[%1$s|%2$s] %3$s');
		$log->debug('Setted another log format');

		$content = file_get_contents('/tmp/test.log');
		$this->assertRegExp(
			 '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\|DEBUG\] Setted another log format/', $content);
	}

	public function testSetLogLevel() {
		$log = new Teacup\Log('/tmp', 'test.log');
		$log->setLevel(Teacup\Log::INFO);
		$log->debug('Setted another log format');

		$this->assertStringEqualsFile('/tmp/test.log', '');
	}

}
