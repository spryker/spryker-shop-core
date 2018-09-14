<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\WishlistWidget\WishlistMenuItemWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\WishlistWidget\WishlistWidgetFactory getFactory()
 */
class WishlistMenuItemWidgetPlugin extends AbstractWidgetPlugin implements WishlistMenuItemWidgetPluginInterface
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
     *
     * @return void
     */
    public function initialize(string $activePage, ?int $activeEntityId = null): void
    {
        $this->activePage = $activePage;
        $this->activeWishlistId = $activeEntityId;

        $this->addActivePageParameter();
        $this->addWishlistCollectionParameter();
        $this->addActiveWishlistIdParameter();
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
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

        return (array)$customerWishlistCollectionTransfer->getWishlists();
    }
}
