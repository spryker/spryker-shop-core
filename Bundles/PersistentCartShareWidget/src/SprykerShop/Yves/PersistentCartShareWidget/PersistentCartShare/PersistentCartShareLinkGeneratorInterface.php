<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare;

interface PersistentCartShareLinkGeneratorInterface
{
    /**
     * @param array $shareOptions
     * @param int $idQuote
     * @param string $shareOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException
     *
     * @return string[]
     */
    public function generateCartShareLinks(array $shareOptions, int $idQuote, string $shareOptionGroup): array;

    /**
     * @param array $shareOptions
     * @param int $idQuote
     * @param string $shareOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException
     *
     * @return string[]
     */
    public function generateCartShareLinkLabels(array $shareOptions, int $idQuote, string $shareOptionGroup): array;

    /**
     * @return array
     */
    public function generateShareOptionGroups(): array;
}
