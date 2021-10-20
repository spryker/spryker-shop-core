<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage;

use Generated\Shared\Transfer\ShopContextTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\WishlistPage\Business\MoveToCartHandler;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface;
use SprykerShop\Yves\WishlistPage\Expander\WishlistItem\WishlistItemExpander;
use SprykerShop\Yves\WishlistPage\Expander\WishlistItem\WishlistItemExpanderIterface;
use SprykerShop\Yves\WishlistPage\Form\AddAllAvailableProductsToCartFormType;
use SprykerShop\Yves\WishlistPage\Form\Cloner\FormCloner;
use SprykerShop\Yves\WishlistPage\Form\DataProvider\AddAllAvailableProductsToCartFormDataProvider;
use SprykerShop\Yves\WishlistPage\Form\DataProvider\WishlistFormDataProvider;
use SprykerShop\Yves\WishlistPage\Form\WishlistAddItemFormType;
use SprykerShop\Yves\WishlistPage\Form\WishlistDeleteFormType;
use SprykerShop\Yves\WishlistPage\Form\WishlistFormType;
use SprykerShop\Yves\WishlistPage\Form\WishlistMoveToCartFormType;
use SprykerShop\Yves\WishlistPage\Form\WishlistRemoveItemFormType;
use SprykerShop\Yves\WishlistPage\Resolver\ShopContextResolver;
use SprykerShop\Yves\WishlistPage\Resolver\ShopContextResolverInterface;
use Symfony\Component\Form\FormInterface;

class WishlistPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientInterface
     */
    public function getCustomerClient(): WishlistPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): WishlistPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Form\Cloner\FormCloner
     */
    public function getFormCloner(): FormCloner
    {
        return new FormCloner();
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getWishlistForm(?WishlistTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(WishlistFormType::class, $data, $options);
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getWishlistDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(WishlistDeleteFormType::class);
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getWishlistRemoveItemForm(): FormInterface
    {
        return $this->getFormFactory()->create(WishlistRemoveItemFormType::class);
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getWishlistAddItemForm(): FormInterface
    {
        return $this->getFormFactory()->create(WishlistAddItemFormType::class);
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getWishlistMoveToCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(WishlistMoveToCartFormType::class);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Form\DataProvider\WishlistFormDataProvider
     */
    public function createWishlistFormDataProvider()
    {
        return new WishlistFormDataProvider(
            $this->getWishlistClient(),
            $this->getCustomerClient(),
        );
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param array<mixed> $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddAllAvailableProductsToCartForm(array $data, array $options = [])
    {
        return $this->getFormFactory()->create(AddAllAvailableProductsToCartFormType::class, $data, $options);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Form\DataProvider\AddAllAvailableProductsToCartFormDataProvider
     */
    public function createAddAllAvailableProductsToCartFormDataProvider()
    {
        return new AddAllAvailableProductsToCartFormDataProvider();
    }

    /**
     * @deprecated
     *
     * @return \SprykerShop\Yves\WishlistPage\Form\WishlistFormType
     */
    public function createWishlistFormType()
    {
        return new WishlistFormType();
    }

    /**
     * @deprecated
     *
     * @return \SprykerShop\Yves\WishlistPage\Form\AddAllAvailableProductsToCartFormType
     */
    public function createAddAllAvailableProductsToCartFormType()
    {
        return new AddAllAvailableProductsToCartFormType();
    }

    /**
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function getShopContext(): ShopContextTransfer
    {
        return $this->createShopContextResolver()->resolve();
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getContainer());
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Expander\WishlistItem\WishlistItemExpanderIterface
     */
    public function createWishlistItemExpander(): WishlistItemExpanderIterface
    {
        return new WishlistItemExpander(
            $this->getWishlistItemRequestExpanderPlugins(),
            $this->getWishlistItemExpanderPlugins(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Business\MoveToCartHandlerInterface
     */
    public function createMoveToCartHandler()
    {
        return new MoveToCartHandler($this->getWishlistClient(), $this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientInterface
     */
    public function getWishlistClient(): WishlistPageToWishlistClientInterface
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::CLIENT_WISHLIST);
    }

    /**
     * @return \SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToProductStorageClientInterface
     */
    public function getProductStorageClient()
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
     */
    public function getWishlistItemExpanderPlugins()
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::PLUGIN_WISHLIST_ITEM_EXPANDERS);
    }

    /**
     * @return array<string>
     */
    public function getWishlistViewWidgetPlugins(): array
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::PLUGIN_WISHLIST_VIEW_WIDGETS);
    }

    /**
     * @return array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface>
     */
    public function getWishlistItemRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::PLUGIN_WISHLIST_ITEM_REQUEST_EXPANDERS);
    }

    /**
     * @return array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemMetaFormExpanderPluginInterface>
     */
    public function getWishlistItemMetaFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(WishlistPageDependencyProvider::PLUGIN_WISHLIST_ITEM_META_FORM_EXPANDERS);
    }
}
