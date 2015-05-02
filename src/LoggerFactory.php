<?php
/**
 * This file is part of the Forestry Log library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/ForestryCodes/log/
 * @package Forestry Log
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Forestry\Log;

/**
 * Abstract class for a factory method implementation
 *
 * @package Forestry Log
 * @since 1.0.0
 */
abstract class LoggerFactory
{
    /**
     * Create a concrete Log instance.
     *
     * Creates a Log instance with settings depending on the implementing class.
     *
     * @param string $fileName
     * @return Log
     */
    abstract protected function createLogger($fileName);

    /**
     * Create a Log instance.
     *
     * Public callable method for the implementing class.
     *
     * @param string $fileName
     * @return Log
     */
    public function create($fileName)
    {
        return $this->createLogger($fileName);
    }
}
