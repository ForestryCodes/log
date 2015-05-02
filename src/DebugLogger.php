<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class DebugLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::DEBUG);
    }
}