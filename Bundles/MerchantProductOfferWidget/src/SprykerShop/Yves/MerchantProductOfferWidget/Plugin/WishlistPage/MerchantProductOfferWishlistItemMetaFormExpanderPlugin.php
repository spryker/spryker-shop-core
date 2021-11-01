<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\WishlistPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemMetaFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantProductOfferWishlistItemMetaFormExpanderPlugin extends AbstractPlugin implements WishlistItemMetaFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItemMetaFormType` with hidden fields for `merchant_reference` and `product_offer_reference`.
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
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('merchant_reference', HiddenType::class, [
            'label' => false,
        ])
        ->add('product_offer_reference', HiddenType::class, [
            'label' => false,
        ]);
    }
}
