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
use Symfony\Component\HttpFoundation\Request;

class OrderCustomReferenceForm extends AbstractType
{
    protected const PATH_ORDER_CUSTOM_REFERENCE_SAVE = 'order-custom-reference/save';

    protected const CLASS_INPUT = 'input input--expand spacing-bottom spacing-bottom--small';

    protected const FIELD_BACK_URL = 'backUrl';
    protected const FIELD_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction(static::PATH_ORDER_CUSTOM_REFERENCE_SAVE);
        $builder->setMethod(Request::METHOD_POST);

        $builder->add(static::FIELD_BACK_URL, HiddenType::class);
        $this->addOrderCustomReferenceField($builder);
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
            TextType::class,
            [
                'attr' => [
                    'class' => static::CLASS_INPUT,
                ],
            ]
        );

        return $this;
    }
}
