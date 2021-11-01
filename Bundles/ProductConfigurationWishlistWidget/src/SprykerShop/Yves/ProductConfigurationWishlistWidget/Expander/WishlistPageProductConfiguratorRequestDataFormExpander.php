<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class WishlistPageProductConfiguratorRequestDataFormExpander implements WishlistPageProductConfiguratorRequestDataFormExpanderInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\ProductConfigurationButtonForm::FILED_ID_WISHLIST_ITEM
     *
     * @var string
     */
    protected const FILED_ID_WISHLIST_ITEM = ProductConfiguratorRequestDataTransfer::ID_WISHLIST_ITEM;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\ProductConfigurationButtonForm::SKU
     *
     * @var string
     */
    protected const FIELD_SKU = ProductConfiguratorRequestDataTransfer::SKU;

    /**
     * @uses \SprykerShop\Yves\ProductConfigurationWishlistWidget\Form\ProductConfigurationButtonForm::FILED_SOURCE_TYPE
     *
     * @var string
     */
    protected const FIELD_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ID_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.wishlist_item_id_required';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.source_type_not_blank';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SKU_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.sku_not_blank';

    /**
     * @var \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig
     */
    protected $productConfigurationWishlistWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig $productConfigurationWishlistWidgetConfig
     */
    public function __construct(ProductConfigurationWishlistWidgetConfig $productConfigurationWishlistWidgetConfig)
    {
        $this->productConfigurationWishlistWidgetConfig = $productConfigurationWishlistWidgetConfig;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandProductConfiguratorRequestDataForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this
            ->addIdWishlistItemField($builder)
            ->addSkuField($builder)
            ->addSourceTypeField($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdWishlistItemField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_ID_WISHLIST_ITEM, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_ID_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SKU_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SOURCE_TYPE, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE]),
                new EqualTo(['value' => $this->productConfigurationWishlistWidgetConfig->getWishlistSourceType()]),
            ],
        ]);

        return $this;
    }
}
