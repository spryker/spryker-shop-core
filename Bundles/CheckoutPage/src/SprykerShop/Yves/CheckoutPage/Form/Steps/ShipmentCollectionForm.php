<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
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
    public const OPTION_SHIPMENT_ADDRESS_LABEL_LIST = 'shippingAddressLabelList';
    public const OPTION_SHIPMENT_GROUPS = 'shipmentGroups';
    public const OPTION_SHIPMENT_METHODS_BY_GROUP = 'shipmentMethodsByGroup';

    protected const BLOCK_PREFIX = 'shipmentCollectionForm';
    protected const FIELD_SHIPMENT_GROUP_COLLECTION = 'shipmentGroups';

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShipmentGroupsSubForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentGroupsSubForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupCollection */
        $shipmentGroupCollection = $options[static::OPTION_SHIPMENT_GROUPS];

        $builder->add(static::FIELD_SHIPMENT_GROUP_COLLECTION, CollectionType::class, [
            'entry_type' => ShipmentGroupForm::class,
            'data' => $shipmentGroupCollection->getArrayCopy(),
            'mapped' => false,
            'entry_options' => [
                static::OPTION_SHIPMENT_GROUPS => $shipmentGroupCollection,
                static::OPTION_SHIPMENT_METHODS_BY_GROUP => $options[static::OPTION_SHIPMENT_METHODS_BY_GROUP],
                static::OPTION_SHIPMENT_ADDRESS_LABEL_LIST => $options[static::OPTION_SHIPMENT_ADDRESS_LABEL_LIST],
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
            ->setRequired(static::OPTION_SHIPMENT_GROUPS)
            ->setRequired(static::OPTION_SHIPMENT_METHODS_BY_GROUP)
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_LABEL_LIST);

        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        $resolver->setDefined(ShipmentForm::OPTION_SHIPMENT_METHODS);
    }
}
