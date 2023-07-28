<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressCollectionFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 */
class ShipmentTypeCheckoutAddressCollectionFormExpanderPlugin extends AbstractPlugin implements CheckoutAddressCollectionFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Configures options for `ShipmentType` subform.
     *
     * @api
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->getFactory()->createShipmentTypeFormOptionExpander()->configureOptions($resolver);
    }

    /**
     * {@inheritDoc}
     * - Expands form options with shipment types.
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
        return $this->getFactory()->createShipmentTypeFormOptionExpander()->expandOptions($quoteTransfer, $options);
    }

    /**
     * {@inheritDoc}
     * - Expands checkout address form with `ShipmentType` subform.
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
            ->createShipmentTypeAddressStepForm()
            ->buildForm($builder, $options);
    }
}
