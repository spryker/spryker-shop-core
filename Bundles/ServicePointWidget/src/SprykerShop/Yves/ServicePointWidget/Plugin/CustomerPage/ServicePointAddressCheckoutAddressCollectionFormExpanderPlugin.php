<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressCollectionFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 */
class ServicePointAddressCheckoutAddressCollectionFormExpanderPlugin extends AbstractPlugin implements CheckoutAddressCollectionFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function expandOptions(QuoteTransfer $quoteTransfer, array $options): array
    {
        return $options;
    }

    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.customer` to be provided.
     * - Expects `QuoteTransfer.item.servicePoint` to be provided.
     * - Expects `QuoteTransfer.item.shipment` to be provided.
     * - Expands shipments with service point address.
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
        return $this->getFactory()
            ->createServicePointAddressExpander()
            ->expandShipmentsWithServicePointAddress($builder);
    }
}
