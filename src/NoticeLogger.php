<?php

namespace Forestry\Log;

use Psr\Log\LogLevel;

class NoticeLogger extends LoggerFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLogger($fileName)
    {
        return new Log($fileName, LogLevel::NOTICE);
    }
}
