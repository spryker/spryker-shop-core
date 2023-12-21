<?php
// phpcs:disable PSR1.Classes.ClassDeclaration

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\UserChecker;

use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\User\InMemoryUserChecker as SymfonyInMemoryUserChecker;
use Symfony\Component\Security\Core\User\UserChecker;

// symfony/routing: <6.0.0
if (class_exists(AuthenticationProviderManager::class)) {
    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     */
    class InMemoryUserChecker extends UserChecker
    {
    }
} else {
    class InMemoryUserChecker extends SymfonyInMemoryUserChecker
    {
    }
}
