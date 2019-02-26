<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class ShipmentCollectionForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentCollectionForm';
    public const FIELD_SHIPMENT_COLLECTION_GROUP = 'shipmentGroups';
    public const OPTION_SHIPMENT_METHODS_BY_GROUP = 'shipmentMethodsByGroup';
    public const OPTION_SHIPMENT_ADDRESS_LABEL_LIST = 'shippingAddressLabelList';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShipmentGroups($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentGroups(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SHIPMENT_COLLECTION_GROUP, CollectionType::class, [
            'entry_type' => ShipmentGroupForm::class,
            'allow_add' => true,
            'allow_delete' => false,
            'entry_options' => [
                'data_class' => ShipmentGroupTransfer::class,
                static::OPTION_SHIPMENT_METHODS_BY_GROUP => $options[static::OPTION_SHIPMENT_METHODS_BY_GROUP] ?? [],
                static::OPTION_SHIPMENT_ADDRESS_LABEL_LIST => $options[static::OPTION_SHIPMENT_ADDRESS_LABEL_LIST] ?? [],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'data_class' => QuoteTransfer::class,
            ])
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP)
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST);
    }
}
