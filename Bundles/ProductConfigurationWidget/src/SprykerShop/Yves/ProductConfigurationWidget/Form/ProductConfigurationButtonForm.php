<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig getConfig()
 */
class ProductConfigurationButtonForm extends AbstractType
{
    public const FILED_SKU = 'sku';
    public const FILED_QUANTITY = 'quantity';
    public const FILED_SOURCE_TYPE = 'sourceType';
    public const FILED_ITEM_GROUP_KEY = 'itemGroupKey';
    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm::PRODUCT_CONFIGURATION_CSRF_TOKEN_ID
     */
    public const PRODUCT_CONFIGURATION_CSRF_TOKEN_ID = 'product_configuration';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSkuField($builder)
            ->addQuantityField($builder)
            ->addSourceField($builder)
            ->addItemGroupKeyField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => static::PRODUCT_CONFIGURATION_CSRF_TOKEN_ID,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SKU, HiddenType::class, [
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemGroupKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_ITEM_GROUP_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_QUANTITY, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SOURCE_TYPE, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new Choice([
                    'choices' => [
                        $this->getConfig()->getCartSourceType(),
                        $this->getConfig()->getPdpSourceType(),
                    ],
                ]),
            ],
        ]);

        return $this;
    }
}
