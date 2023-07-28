<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Form;

use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicePointSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_SELECTED_SERVICE_POINT = 'option_selected_service_point';

    /**
     * @var string
     */
    public const FIELD_SERVICE_POINT_UUID = 'uuid';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ServicePointTransfer::class,
        ]);

        $resolver->setDefined([
            static::OPTION_SELECTED_SERVICE_POINT,
        ]);
        $resolver->setRequired([
            static::OPTION_SELECTED_SERVICE_POINT,
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
        $this->addServicePointUuidField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addServicePointUuidField(FormBuilderInterface $builder, array $options)
    {
        $builder->setData($options[static::OPTION_SELECTED_SERVICE_POINT]);
        $builder->add(static::FIELD_SERVICE_POINT_UUID, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }
}
