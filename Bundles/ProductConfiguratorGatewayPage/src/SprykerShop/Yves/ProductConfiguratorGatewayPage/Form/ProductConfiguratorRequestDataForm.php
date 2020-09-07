<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint\Quantity;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductConfiguratorRequestDataForm extends AbstractType
{
    public const FILED_SKU = 'sku';
    public const FILED_QUANTITY = 'quantity';
    public const FILED_SOURCE_TYPE = 'sourceType';
    public const FILED_ITEM_GROUP_KEY = 'itemGroupKey';
    public const PRODUCT_CONFIGURATION_CSRF_TOKEN_ID = 'product_configuration';

    protected const VALIDATION_SOURCE_NOT_BLANK_MESSAGE = 'product_configuration.source_not_blank';
    protected const VALIDATION_SKU_NOT_BLANK_MESSAGE = 'product_configurator.sku_not_blank';

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
            'data_class' => ProductConfiguratorRequestDataTransfer::class,
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
            'constraints' => [
                new NotBlank(['message' => static::VALIDATION_SKU_NOT_BLANK_MESSAGE]),
            ],
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
            'constraints' => [
               new Quantity(),
            ],
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
                new NotBlank(['message' => static::VALIDATION_SOURCE_NOT_BLANK_MESSAGE]),
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
