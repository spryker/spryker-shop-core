<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Controller;

use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\WishlistItemMetaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use SprykerShop\Yves\WishlistPage\Form\AddAllAvailableProductsToCartFormType;
use SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\WishlistPage\WishlistPageFactory getFactory()
 */
class WishlistController extends AbstractController
{
    /**
     * @var string
     */
    public const DEFAULT_NAME = 'My wishlist';

    /**
     * @var int
     */
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @var string
     */
    public const PARAM_ITEMS_PER_PAGE = 'ipp';

    /**
     * @var string
     */
    public const PARAM_PAGE = 'page';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ID = 'product-id';

    /**
     * @var string
     */
    public const PARAM_SKU = 'sku';

    /**
     * @var string
     */
    public const PARAM_WISHLIST_NAME = 'wishlist-name';

    /**
     * @var string
     */
    public const PARAM_WISHLIST_ID_ITEM = 'id-wishlist-item';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction($wishlistName, Request $request)
    {
        $viewData = $this->executeIndexAction($wishlistName, $request);

        return $this->view(
            $viewData,
            $this->getFactory()->getWishlistViewWidgetPlugins(),
            '@WishlistPage/views/wishlist-detail/wishlist-detail.twig',
        );
    }

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction($wishlistName, Request $request): array
    {
        $wishlistOverviewResponseTransfer = $this->getWishlistOverview($request, $wishlistName);

        $wishlistItems = $this->getWishlistItems($wishlistOverviewResponseTransfer);

        $addAllAvailableProductsToCartForm = $this->createAddAllAvailableProductsToCartForm($wishlistOverviewResponseTransfer);

        return [
            'wishlistItems' => $wishlistItems,
            'wishlistOverview' => $wishlistOverviewResponseTransfer,
            'indexedWishlistItems' => $this->getWishlistItemsIndexedByIdWishlistItem($wishlistOverviewResponseTransfer),
            'currentPage' => $wishlistOverviewResponseTransfer->getPagination()->getPage(),
            'totalPages' => $wishlistOverviewResponseTransfer->getPagination()->getPagesTotal(),
            'wishlistName' => $wishlistName,
            'addAllAvailableProductsToCartForm' => $addAllAvailableProductsToCartForm->createView(),
            'wishlistRemoveItemFormCloner' => $this->getFactory()->getFormCloner()->setForm($this->getFactory()->getWishlistRemoveItemForm()),
            'wishlistMoveToCartFormCloner' => $this->getFactory()->getFormCloner()->setForm($this->getFactory()->getWishlistMoveToCartForm()),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addItemAction(Request $request)
    {
        $wishlistItemTransfer = $this->getWishlistItemTransferFromRequest($request);
        if (!$wishlistItemTransfer) {
            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN);
        }

        $wishlistAddItemForm = $this->getFactory()->getWishlistAddItemForm()->handleRequest($request);

        if (!$wishlistAddItemForm->isSubmitted() || !$wishlistAddItemForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
                'wishlistName' => $wishlistItemTransfer->getWishlistName(),
            ]);
        }

        $wishlistResponseTransfer = new WishlistResponseTransfer();
        if ($wishlistItemTransfer->getWishlistName() === static::DEFAULT_NAME) {
            $wishlistResponseTransfer = $this->getFactory()->getWishlistClient()->validateAndCreateWishlist(
                (new WishlistTransfer())
                    ->setName(static::DEFAULT_NAME)
                ->setFkCustomer($wishlistItemTransfer->getFkCustomer()),
            );
        }

        $wishlistItemTransfer = $this->getFactory()
            ->getWishlistClient()
            ->addItem($wishlistItemTransfer);

        if (!$wishlistItemTransfer->getIdWishlistItem()) {
            if ($wishlistResponseTransfer->getWishlist()) {
                $this->getFactory()->getWishlistClient()->removeWishlistByName($wishlistResponseTransfer->getWishlist());
            }

            $this->addErrorMessage('customer.account.wishlist.item.not_added');

            return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_OVERVIEW, [
                'wishlistName' => $wishlistItemTransfer->getWishlistName(),
            ]);
        }

        return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
            'wishlistName' => $wishlistItemTransfer->getWishlistName(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemAction(Request $request)
    {
        $wishlistItemTransfer = $this->getWishlistItemTransferFromRequest($request);
        if (!$wishlistItemTransfer) {
            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN);
        }

        $wishlistRemoveItemForm = $this->getFactory()->getWishlistRemoveItemForm()->handleRequest($request);

        if (!$wishlistRemoveItemForm->isSubmitted() || !$wishlistRemoveItemForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
                'wishlistName' => $wishlistItemTransfer->getWishlistName(),
            ]);
        }

        $this->getFactory()
            ->getWishlistClient()
            ->removeItem($wishlistItemTransfer);

        $this->addSuccessMessage('customer.account.wishlist.item.removed');

        return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
            'wishlistName' => $wishlistItemTransfer->getWishlistName(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveToCartAction(Request $request)
    {
        $wishlistItemTransfer = $this->getWishlistItemTransferFromRequest($request);
        if (!$wishlistItemTransfer) {
            return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN);
        }

        $wishlistMoveToCartForm = $this->getFactory()->getWishlistMoveToCartForm()->handleRequest($request);

        if (!$wishlistMoveToCartForm->isSubmitted() || !$wishlistMoveToCartForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
                'wishlistName' => $wishlistItemTransfer->getWishlistName(),
            ]);
        }

        $wishlistItemMetaTransferCollection = [
            (new WishlistItemMetaTransfer())
                ->fromArray($wishlistItemTransfer->toArray(), true),
        ];

        $result = $this->getFactory()
            ->createMoveToCartHandler()
            ->moveAllAvailableToCart(
                $wishlistItemTransfer->getWishlistName(),
                $wishlistItemMetaTransferCollection,
            );

        if ($result->getRequests()->count()) {
            $this->addErrorMessage('customer.account.wishlist.item.moved_to_cart.failed');

            return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
                'wishlistName' => $wishlistItemTransfer->getWishlistName(),
            ]);
        }

        $this->addSuccessMessage('customer.account.wishlist.item.moved_to_cart');

        return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
            'wishlistName' => $wishlistItemTransfer->getWishlistName(),
        ]);
    }

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveAllAvailableToCartAction($wishlistName, Request $request)
    {
        $wishlistOverviewResponseTransfer = $this->getWishlistOverview($request, $wishlistName);

        $addAllAvailableProductsToCartForm = $this
            ->createAddAllAvailableProductsToCartForm($wishlistOverviewResponseTransfer)
            ->handleRequest($request);

        if ($addAllAvailableProductsToCartForm->isSubmitted() && $addAllAvailableProductsToCartForm->isValid()) {
            $wishlistItemMetaTransferCollection = $addAllAvailableProductsToCartForm
                ->get(AddAllAvailableProductsToCartFormType::WISHLIST_ITEM_META_COLLECTION)
                ->getData();

            $result = $this->getFactory()
                ->createMoveToCartHandler()
                ->moveAllAvailableToCart($wishlistName, $wishlistItemMetaTransferCollection);

            if ($result->getRequests()->count()) {
                $this->addErrorMessage('customer.account.wishlist.item.moved_all_available_to_cart.failed');

                if ($result->getRequests()->count() === count($wishlistItemMetaTransferCollection)) {
                    return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
                        'wishlistName' => $wishlistName,
                    ]);
                }
            }

            $this->addSuccessMessage('customer.account.wishlist.item.moved_all_available_to_cart');
        }

        return $this->redirectResponseInternal(WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS, [
            'wishlistName' => $wishlistName,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    protected function getPageNumber(Request $request)
    {
        $pageNumber = $request->query->getInt(static::PARAM_PAGE, 1);
        $pageNumber = $pageNumber <= 0 ? 1 : $pageNumber;

        return $pageNumber;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    protected function getItemsPerPage(Request $request)
    {
        $itemsPerPage = $request->query->getInt(static::PARAM_ITEMS_PER_PAGE, static::DEFAULT_ITEMS_PER_PAGE);
        $itemsPerPage = ($itemsPerPage <= 0) ? 1 : $itemsPerPage;
        $itemsPerPage = ($itemsPerPage > 100) ? 10 : $itemsPerPage;

        return $itemsPerPage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    protected function getWishlistItemTransferFromRequest(Request $request)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return null;
        }
        $wishlistName = $request->get(static::PARAM_WISHLIST_NAME) ?: static::DEFAULT_NAME;

        $sku = (string)$request->query->get(static::PARAM_SKU) ?: null;
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdProduct($request->query->getInt(static::PARAM_PRODUCT_ID))
            ->setSku($sku)
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setWishlistName($wishlistName);

        $idWishlistItem = $request->request->getInt(static::PARAM_WISHLIST_ID_ITEM);
        if ($request->request->has(static::PARAM_WISHLIST_ID_ITEM)) {
            $wishlistItemTransfer->setIdWishlistItem($idWishlistItem);
        }

        $requestParams = $request->request->all();

        return $this->getFactory()
            ->createWishlistItemExpander()
            ->expandWishlistItemTransferWithRequestedParams($wishlistItemTransfer, $requestParams);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponse
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    protected function createAddAllAvailableProductsToCartForm(?WishlistOverviewResponseTransfer $wishlistOverviewResponse = null)
    {
        $addAllAvailableProductsToCartFormDataProvider = $this->getFactory()->createAddAllAvailableProductsToCartFormDataProvider();
        $addAllAvailableProductsToCartForm = $this->getFactory()->getAddAllAvailableProductsToCartForm(
            $addAllAvailableProductsToCartFormDataProvider->getData($wishlistOverviewResponse),
        );

        return $addAllAvailableProductsToCartForm;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponse
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function getWishlistItems(WishlistOverviewResponseTransfer $wishlistOverviewResponse): array
    {
        $wishlistItems = [];
        foreach ($wishlistOverviewResponse->getItems() as $wishlistItemTransfer) {
            $wishlistItems[$wishlistItemTransfer->getIdWishlistItem()] = $this->createProductView($wishlistItemTransfer);
        }

        return $wishlistItems;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductView(WishlistItemTransfer $wishlistItemTransfer)
    {
        $productConcreteStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageData($wishlistItemTransfer->getIdProduct(), $this->getLocale());

        if ($productConcreteStorageData === null) {
            return $this->prepareUnavailableProduct($wishlistItemTransfer);
        }

        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->fromArray($wishlistItemTransfer->toArray(), true);

        return $this->prepareConcreteProduct($productViewTransfer, $productConcreteStorageData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<mixed> $productConcreteStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function prepareConcreteProduct(
        ProductViewTransfer $productViewTransfer,
        array $productConcreteStorageData
    ): ProductViewTransfer {
        $productViewTransfer = $this->mapProductConcreteStorageDataToProductViewTransfer(
            $productConcreteStorageData,
            $productViewTransfer,
        );

        return $this->getFactory()
            ->createWishlistItemExpander()
            ->expandProductViewTransferWithProductConcreteData(
                $productViewTransfer,
                $productConcreteStorageData,
                $this->getLocale(),
            );
    }

    /**
     * @param array<mixed> $productConcreteStorageData
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function mapProductConcreteStorageDataToProductViewTransfer(
        array $productConcreteStorageData,
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        $productViewData = array_replace(
            $productConcreteStorageData,
            array_filter($productViewTransfer->toArray(), function ($value) {
                return $value !== null;
            }),
        );

        return $productViewTransfer->fromArray($productViewData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function prepareUnavailableProduct(WishlistItemTransfer $wishlistItemTransfer): ProductViewTransfer
    {
        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->setSku($wishlistItemTransfer->getSku());
        $productViewTransfer->setIdProductConcrete($wishlistItemTransfer->getIdProduct());
        $productViewTransfer->addImage(new ProductImageStorageTransfer());

        return $productViewTransfer;
    }

    /**
     * @param string $key
     * @param array<string> $parameters
     *
     * @return string
     */
    protected function translate(string $key, array $parameters = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $this->getLocale(), $parameters);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $wishlistName
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    protected function getWishlistOverview(Request $request, string $wishlistName): WishlistOverviewResponseTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $wishlistTransfer = (new WishlistTransfer())
            ->setName($wishlistName)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $shopContextParams = $this->getFactory()
            ->getShopContext()
            ->toArray();
        $wishlistTransfer->fromArray($shopContextParams, true);

        $wishlistOverviewRequestTransfer = (new WishlistOverviewRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setPage($this->getPageNumber($request))
            ->setItemsPerPage($this->getItemsPerPage($request));

        $wishlistOverviewResponseTransfer = $this->getFactory()
            ->getWishlistClient()
            ->getWishlistOverviewWithoutProductDetails($wishlistOverviewRequestTransfer);

        $this->handleWishlistErrors($wishlistOverviewResponseTransfer);

        if (!$wishlistOverviewResponseTransfer->getWishlist()->getIdWishlist()) {
            throw new NotFoundHttpException();
        }

        return $wishlistOverviewResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     *
     * @return void
     */
    protected function handleWishlistErrors(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer): void
    {
        foreach ($wishlistOverviewResponseTransfer->getErrors() as $messageTransfer) {
            $translatedMessage = $this->translate(
                $messageTransfer->getMessage(),
                $messageTransfer->getParameters(),
            );

            $this->addErrorMessage($translatedMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     *
     * @return array<\Generated\Shared\Transfer\WishlistItemTransfer>
     */
    protected function getWishlistItemsIndexedByIdWishlistItem(
        WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
    ): array {
        $indexedWishlistItemTransfers = [];

        foreach ($wishlistOverviewResponseTransfer->getItems() as $wishlistItemTransfer) {
            $indexedWishlistItemTransfers[$wishlistItemTransfer->getIdWishlistItem()] = $wishlistItemTransfer;
        }

        return $indexedWishlistItemTransfers;
    }
}
