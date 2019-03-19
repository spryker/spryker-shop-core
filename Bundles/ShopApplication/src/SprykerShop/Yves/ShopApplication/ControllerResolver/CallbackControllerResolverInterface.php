<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\ControllerResolver;

interface CallbackControllerResolverInterface
{
    /**
     * @param mixed $name
     *
     * @return bool
     */
    public function isValid($name): bool;

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function convertCallback(string $name): array;

    /**
     * @param string $name
     *
     * @return array
     */
    public function resolveCallback(string $name): array;
}
