<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Model;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

interface AgentRedirectHandlerInterface
{
    /**
     * Redirect agent to login page on AccessDeniedException
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    public function redirect(GetResponseEvent $event): void;
}
