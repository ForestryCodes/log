<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class EmergencyLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::EMERGENCY);
    }
}
