<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\WishlistWidget\WishlistWidgetFactory getFactory()
 */
class WishlistMenuItemWidget extends AbstractWidget
{
    protected const PAGE_KEY_WISHLIST = 'wishlist';

    /**
     * @var string
     */
    protected $activePage;

    /**
     * @var int
     */
    protected $activeWishlistId;

    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     */
    public function __construct(string $activePage, ?int $activeEntityId = null)
    {
        $this->activePage = $activePage;
        $this->activeWishlistId = $activeEntityId;

        $this->addActivePageParameter();
        $this->addWishlistCollectionParameter();
        $this->addActiveWishlistIdParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'WishlistMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@WishlistWidget/views/wishlist-menu-item/wishlist-menu-item.twig';
    }

    /**
     * @return void
     */
    protected function addActivePageParameter(): void
    {
        $this->addParameter('isActivePage', $this->isWishlistPageActive());
    }

    /**
     * @return void
     */
    protected function addWishlistCollectionParameter(): void
    {
        $this->addParameter('wishlistCollection', $this->isWishlistPageActive() ? $this->getCustomerWishlistCollection() : []);
    }

    /**
     * @return void
     */
    protected function addActiveWishlistIdParameter(): void
    {
        $this->addParameter('activeWishlistId', $this->isWishlistPageActive() ? $this->activeWishlistId : []);
    }

    /**
     * @return bool
     */
    protected function isWishlistPageActive(): bool
    {
        return $this->activePage === static::PAGE_KEY_WISHLIST;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer[]
     */
    protected function getCustomerWishlistCollection(): array
    {
        $customerWishlistCollectionTransfer = $this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection();

        if ($customerWishlistCollectionTransfer->getWishlists() === null) {
            return [];
        }

        return (array)$customerWishlistCollectionTransfer->getWishlists();
    }
}
