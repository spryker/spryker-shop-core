<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class AddAllAvailableProductsToCartFormType extends AbstractType
{
    /**
     * @var string
     */
    public const WISHLIST_ITEM_META_COLLECTION = 'wishlistItemMetaCollection';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addWishlistItemMetaCollectionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addWishlistItemMetaCollectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::WISHLIST_ITEM_META_COLLECTION, CollectionType::class, [
            'label' => false,
            'entry_type' => WishlistItemMetaFormType::class,
            'allow_add' => true,
        ]);

        return $this;
    }
}
