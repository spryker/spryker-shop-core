<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\Builder;

use Psr\Log\LoggerInterface;

interface FrontendBuilderInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function build(LoggerInterface $logger): bool;
}
