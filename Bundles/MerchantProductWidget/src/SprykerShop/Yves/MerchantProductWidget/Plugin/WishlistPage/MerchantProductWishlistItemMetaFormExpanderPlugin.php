<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\WishlistPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemMetaFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantProductWishlistItemMetaFormExpanderPlugin extends AbstractPlugin implements WishlistItemMetaFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItemMetaFormType` with hidden field for 'merchant_reference'.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('merchant_reference', HiddenType::class, [
            'label' => false,
        ]);
    }
}
