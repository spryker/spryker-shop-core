<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Form;

use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentTypeSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_SHIPMENT_TYPES = 'option_shipment_types';

    /**
     * @var string
     */
    public const OPTION_AVAILABLE_SHIPMENT_TYPES = 'option_available_shipment_types';

    /**
     * @var string
     */
    public const OPTION_SELECTED_SHIPMENT_TYPE = 'option_selected_shipment_type';

    /**
     * @var string
     */
    public const FIELD_SHIPMENT_TYPE_KEY = 'key';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ShipmentTypeTransfer::class,
        ]);

        $resolver->setDefined([
            static::OPTION_SHIPMENT_TYPES,
            static::OPTION_AVAILABLE_SHIPMENT_TYPES,
            static::OPTION_SELECTED_SHIPMENT_TYPE,
        ]);
        $resolver->setRequired([
            static::OPTION_SHIPMENT_TYPES,
            static::OPTION_AVAILABLE_SHIPMENT_TYPES,
            static::OPTION_SELECTED_SHIPMENT_TYPE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShipmentTypeKeyField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addShipmentTypeKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SHIPMENT_TYPE_KEY, ChoiceType::class, [
            'choices' => $this->transformShipmentTypesToChoices($options[static::OPTION_SHIPMENT_TYPES]),
            'label' => false,
            'expanded' => true,
            'data' => $options[static::OPTION_SELECTED_SHIPMENT_TYPE],
            'preferred_choices' => [static::SHIPMENT_TYPE_DELIVERY],
            'choice_attr' => function ($key) use ($options) {
                $availableShipmentType = $options[static::OPTION_AVAILABLE_SHIPMENT_TYPES][$key] ?? null;

                return $availableShipmentType ? [] : ['disabled' => 'disabled'];
            },
        ]);

        return $this;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<string, string>
     */
    protected function transformShipmentTypesToChoices(array $shipmentTypeTransfers): array
    {
        $shipmentTypeChoices = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeChoices[$shipmentTypeTransfer->getNameOrFail()] = $shipmentTypeTransfer->getKeyOrFail();
        }

        return $shipmentTypeChoices;
    }
}
