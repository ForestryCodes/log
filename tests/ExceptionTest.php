<?php

namespace Forestry\Log\Test;

use Forestry\Log\Log;

/**
 * Class ExceptionTest
 *
 * Test case for all exceptions thrown by the package.
 *
 * @package Forestry\Log
 * @subpackage Forestry\Log\Test
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \Forestry\Log\DirectoryException
     */
    public function testThrowsExceptionWhenDirectoryDoesNotExist()
    {
        new Log('/tmp/test/' . $this->testFile);
    }

    /**
     * @expectedException \Forestry\Log\DirectoryException
     */
    public function testThrowsExceptionWhenDirectoryDoesntHaveWritePermissions()
    {
        new Log('/root/' . $this->testFile);
    }

    /**
     * @expectedException \Forestry\Log\FileException
     */
    public function testThrowsExceptionWhenHandleCantBeOpened()
    {
        //Suppressing errors only for testing purpose.
        @new Log('/tmp/.');
    }

    /**
     * @expectedException \Forestry\Log\InvalidArgumentException
     */
    public function testThrowsExceptionOnUndefinedLogLevel()
    {
        new Log('/tmp/' . $this->testFile, 100);
    }

    /**
     * @expectedException \Forestry\Log\InvalidArgumentException
     */
    public function testLogThrowsExceptionOnUndefinedLogLevel()
    {
        $logger = new Log('/tmp/' . $this->testFile);
        $logger->log(100, 'What level is this?');
    }
}
