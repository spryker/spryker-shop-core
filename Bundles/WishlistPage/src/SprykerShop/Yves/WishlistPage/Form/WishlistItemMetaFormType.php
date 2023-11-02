<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Form;

use Generated\Shared\Transfer\WishlistItemMetaTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\WishlistPage\WishlistPageFactory getFactory()
 */
class WishlistItemMetaFormType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var string
     */
    public const FIELD_ID_WISHLIST_ITEM = 'idWishlistItem';

    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT = 'idProduct';

    /**
     * @var string
     */
    public const FIELD_SKU = 'sku';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WishlistItemMetaTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdProductAbstractField($builder)
            ->addIdProductField($builder)
            ->addSkuField($builder)
            ->addIdWishlistItemField($builder);

        foreach ($this->getFactory()->getWishlistItemMetaFormExpanderPlugins() as $wishlistItemMetaFormExpanderPlugin) {
            $wishlistItemMetaFormExpanderPlugin->expand($builder, $options);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIdProductAbstractField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACT, HiddenType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIdProductField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT, HiddenType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addIdWishlistItemField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_WISHLIST_ITEM, HiddenType::class, [
            'label' => false,
        ]);

        return $this;
    }
}
