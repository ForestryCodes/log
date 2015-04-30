<?php

namespace Forestry\Log;

abstract class LoggerFactory
{
    /**
     * Create a concrete Log instance.
     *
     * @param string $fileName
     * @return Log
     */
    abstract protected function createLogger($fileName);

    /**
     * Create a Log instance.
     *
     * @param string $fileName
     * @return Log
     */
    public function create($fileName)
    {
        return $this->createLogger($fileName);
    }
}
