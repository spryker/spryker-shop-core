<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Form;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class ProductConfigurationButtonForm extends AbstractType
{
    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm::PRODUCT_CONFIGURATION_CSRF_TOKEN_ID
     *
     * @var string
     */
    public const PRODUCT_CONFIGURATION_CSRF_TOKEN_ID = 'product_configuration';

    /**
     * @var string
     */
    protected const FILED_ID_WISHLIST_ITEM = ProductConfiguratorRequestDataTransfer::ID_WISHLIST_ITEM;

    /**
     * @var string
     */
    protected const FIELD_SKU = ProductConfiguratorRequestDataTransfer::SKU;

    /**
     * @var string
     */
    protected const FILED_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * @var string
     */
    protected const FIELD_CONFIGURATOR_KEY = ProductConfiguratorRequestDataTransfer::CONFIGURATOR_KEY;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdWishlistItemField($builder)
            ->addSkuField($builder)
            ->addSourceTypeField($builder)
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
    protected function addIdWishlistItemField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_ID_WISHLIST_ITEM, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class);

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
