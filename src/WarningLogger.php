<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class WarningLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::WARNING);
    }
}
