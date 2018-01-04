<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class AddAllAvailableProductsToCartFormType extends AbstractType
{
    const WISHLIST_ITEM_META_COLLECTION = 'wishlistItemMetaCollection';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
        $builder->add(self::WISHLIST_ITEM_META_COLLECTION, CollectionType::class, [
            'label' => false,
            'entry_type' => WishlistItemMetaFormType::class,
            'allow_add' => true,
        ]);

        return $this;
    }
}
