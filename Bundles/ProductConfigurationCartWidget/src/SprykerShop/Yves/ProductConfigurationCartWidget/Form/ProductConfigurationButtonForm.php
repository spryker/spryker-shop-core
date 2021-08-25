<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Form;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class ProductConfigurationButtonForm extends AbstractType
{
    protected const FILED_SKU = ProductConfiguratorRequestDataTransfer::SKU;
    protected const FILED_QUANTITY = ProductConfiguratorRequestDataTransfer::QUANTITY;
    protected const FILED_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;
    protected const FILED_ITEM_GROUP_KEY = ProductConfiguratorRequestDataTransfer::ITEM_GROUP_KEY;
    protected const FIELD_CONFIGURATOR_KEY = ProductConfiguratorRequestDataTransfer::CONFIGURATOR_KEY;

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
            ->addSourceTypeField($builder)
            ->addItemGroupKeyField($builder)
            ->addConfiguratorKeyField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
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
        $builder->add(static::FILED_SKU, HiddenType::class);

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
        $builder->add(static::FILED_QUANTITY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SOURCE_TYPE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addConfiguratorKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONFIGURATOR_KEY, HiddenType::class);

        return $this;
    }
}
