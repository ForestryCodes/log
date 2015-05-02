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

use Psr\Log\LogLevel;

/**
 * Factory class for a Log instance with an alert level threshold
 *
 * @package Forestry Log
 * @since 1.0.0
 */
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
