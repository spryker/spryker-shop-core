<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderCustomReferenceForm extends AbstractType
{
    public const FIELD_BACK_URL = 'backUrl';
    public const FIELD_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addOrderCustomReferenceField($builder)
            ->addBackUrlField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBackUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_BACK_URL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderCustomReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ORDER_CUSTOM_REFERENCE,
            TextType::class
        );

        return $this;
    }
}
