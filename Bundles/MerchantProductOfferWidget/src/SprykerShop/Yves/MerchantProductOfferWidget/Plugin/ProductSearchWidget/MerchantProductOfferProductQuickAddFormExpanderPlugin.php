<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\ProductSearchWidget;

use SprykerShop\Yves\ProductSearchWidgetExtension\Dependency\Plugin\ProductQuickAddFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MerchantProductOfferProductQuickAddFormExpanderPlugin implements ProductQuickAddFormExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * {@inheritDoc}
     * - Expands `ProductQuickAddForm` with `product_offer_reference` hidden field.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();
            $form->add(static::FIELD_PRODUCT_OFFER_REFERENCE, HiddenType::class);
        });

        return $builder;
    }
}
