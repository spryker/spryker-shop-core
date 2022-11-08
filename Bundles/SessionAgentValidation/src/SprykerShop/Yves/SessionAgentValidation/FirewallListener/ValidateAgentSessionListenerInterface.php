<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\FirewallListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface ValidateAgentSessionListenerInterface
{
    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return void
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void;
}
