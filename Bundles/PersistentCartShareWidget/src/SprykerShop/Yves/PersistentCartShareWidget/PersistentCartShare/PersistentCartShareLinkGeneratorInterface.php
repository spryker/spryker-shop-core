<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare;

interface PersistentCartShareLinkGeneratorInterface
{
    /**
     * @param array $cartShareOptions
     * @param int $idQuote
     * @param string $permissionOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidPermissionOptionException
     *
     * @return string[]
     */
    public function generateCartShareLinks(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array;

    /**
     * @param array $cartShareOptions
     * @param int $idQuote
     * @param string $permissionOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidPermissionOptionException
     *
     * @return string[]
     */
    public function generateCartShareLinkLabels(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array;

    /**
     * @return string[]
     */
    public function generateCartShareOptionGroups(): array;
}
