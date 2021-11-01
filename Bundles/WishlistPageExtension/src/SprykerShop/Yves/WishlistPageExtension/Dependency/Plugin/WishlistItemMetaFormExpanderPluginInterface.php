<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides ability to expand `WishlistItemMetaFormType` with fields.
 */
interface WishlistItemMetaFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `WishlistItemMetaFormType` with additional fields.
     *
     * @api
     *
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void;
}
