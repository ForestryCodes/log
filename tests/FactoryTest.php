<?php

namespace Forestry\Log\Test;

use Forestry\Log\DebugLogger;
use Forestry\Log\InfoLogger;
use Forestry\Log\NoticeLogger;
use Forestry\Log\WarningLogger;
use Forestry\Log\ErrorLogger;
use Forestry\Log\CriticalLogger;
use Forestry\Log\AlertLogger;
use Forestry\Log\EmergencyLogger;
use Psr\Log\LogLevel;

/**
 * Class FactoryTest
 *
 * Test case for the factory method.
 *
 * @package Forestry\Log
 * @subpackage Forestry\Log\Test
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $testFile = '/tmp/forestry-log-test.log';

    public function testDebugLogger()
    {
        $factory = new DebugLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::DEBUG);
    }

    public function testInfoLogger()
    {
        $factory = new InfoLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::INFO);
    }

    public function testNoticeLogger()
    {
        $factory = new NoticeLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::NOTICE);
    }

    public function testWarningLogger()
    {
        $factory = new WarningLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::WARNING);
    }

    public function testErrorLogger()
    {
        $factory = new ErrorLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::ERROR);
    }

    public function testCriticalLogger()
    {
        $factory = new CriticalLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::CRITICAL);
    }

    public function testAlertLogger()
    {
        $factory = new AlertLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::ALERT);
    }

    public function testEmergencyLogger()
    {
        $factory = new EmergencyLogger();
        $logger = $factory->create($this->testFile);

        $this->assertEquals($logger->getLogThreshold(), LogLevel::EMERGENCY);
    }
}
