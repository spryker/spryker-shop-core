<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutMultiShippingAddressesFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 */
class ShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin extends AbstractPlugin implements CheckoutMultiShippingAddressesFormExpanderPluginInterface
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
     * - Expands checkout multi-shipping address form with `ShipmentType` subform.
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
        $this->getFactory()
            ->createShipmentTypeAddressStepForm()
            ->buildForm($builder, $options);

        return $builder;
    }
}
