<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutMultiShippingAddressesFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 */
class ClickCollectServiceTypeCheckoutMultiShippingAddressesFormExpanderPlugin extends AbstractPlugin implements CheckoutMultiShippingAddressesFormExpanderPluginInterface
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
     * - Expands `ServicePoint` subform with pickupable service type.
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
            ->createClickCollectServiceTypeSubForm()
            ->buildForm($builder, $options);
    }
}
