<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class AlertLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::ALERT);
    }
}
