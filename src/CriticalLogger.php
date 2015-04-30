<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class CriticalLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::CRITICAL);
    }
}
