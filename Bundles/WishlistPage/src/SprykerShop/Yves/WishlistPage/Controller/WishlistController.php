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
    public const DEFAULT_NAME = 'My wishlist';
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    public const PARAM_ITEMS_PER_PAGE = 'ipp';
    public const PARAM_PAGE = 'page';
    public const PARAM_PRODUCT_ID = 'product-id';
    public const PARAM_SKU = 'sku';
    public const PARAM_WISHLIST_NAME = 'wishlist-name';
    public const PARAM_WISHLIST_ID_ITEM = 'id-wishlist-item';
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
            '@WishlistPage/views/wishlist-detail/wishlist-detail.twig'
        );
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeIndexAction($wishlistName, Request $request): array
    {
        $pageNumber = $this->getPageNumber($request);
        $itemsPerPage = $this->getItemsPerPage($request);

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

        $wishlistOverviewRequest = (new WishlistOverviewRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setPage($pageNumber)
            ->setItemsPerPage($itemsPerPage);

        $wishlistOverviewResponse = $this->getFactory()
            ->getWishlistClient()
            ->getWishlistOverviewWithoutProductDetails($wishlistOverviewRequest);

        if ($wishlistOverviewResponse->getErrors()->count() > 0) {
            foreach ($wishlistOverviewResponse->getErrors() as $errorMessageTransfer) {
                $translatedMessage = $this->translate(
                    $errorMessageTransfer->getMessage(),
                    $errorMessageTransfer->getParameters()
                );

                $this->addErrorMessage($translatedMessage);
            }
        }

        if (!$wishlistOverviewResponse->getWishlist()->getIdWishlist()) {
            throw new NotFoundHttpException();
        }

        $wishlistItems = $this->getWishlistItems($wishlistOverviewResponse);

        $addAllAvailableProductsToCartForm = $this->createAddAllAvailableProductsToCartForm($wishlistOverviewResponse);

        return [
            'wishlistItems' => $wishlistItems,
            'wishlistOverview' => $wishlistOverviewResponse,
            'currentPage' => $wishlistOverviewResponse->getPagination()->getPage(),
            'totalPages' => $wishlistOverviewResponse->getPagination()->getPagesTotal(),
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

        $wishlistItemTransfer = $this->getFactory()
            ->getWishlistClient()
            ->addItem($wishlistItemTransfer);
        if (!$wishlistItemTransfer->getIdWishlistItem()) {
            $this->addErrorMessage('customer.account.wishlist.item.not_added');
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
                $wishlistItemMetaTransferCollection
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
        $addAllAvailableProductsToCartForm = $this
            ->createAddAllAvailableProductsToCartForm()
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
        $pageNumber = $request->query->getInt(self::PARAM_PAGE, 1);
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
        $itemsPerPage = $request->query->getInt(self::PARAM_ITEMS_PER_PAGE, self::DEFAULT_ITEMS_PER_PAGE);
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
        $customerClient = $this->getFactory()->getCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        if (!$customerTransfer) {
            return null;
        }

        $wishlistName = $request->get(self::PARAM_WISHLIST_NAME) ?: self::DEFAULT_NAME;

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setIdProduct($request->query->getInt(self::PARAM_PRODUCT_ID))
            ->setSku($request->query->get(self::PARAM_SKU))
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setWishlistName($wishlistName);

        if ($request->request->has(static::PARAM_WISHLIST_ID_ITEM)) {
            $wishlistItemTransfer->setIdWishlistItem($request->request->get(static::PARAM_WISHLIST_ID_ITEM));
        }

        $requestParams = $request->request->all();

        return $this->getFactory()
            ->createWishlistItemExpander()
            ->expandWishlistItemTransferWithRequestedParams($wishlistItemTransfer, $requestParams);
    }

    /**
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponse
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createAddAllAvailableProductsToCartForm(?WishlistOverviewResponseTransfer $wishlistOverviewResponse = null)
    {
        $addAllAvailableProductsToCartFormDataProvider = $this->getFactory()->createAddAllAvailableProductsToCartFormDataProvider();
        $addAllAvailableProductsToCartForm = $this->getFactory()->getAddAllAvailableProductsToCartForm(
            $addAllAvailableProductsToCartFormDataProvider->getData($wishlistOverviewResponse)
        );

        return $addAllAvailableProductsToCartForm;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponse
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
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
     * @phpstan-param array<mixed> $productConcreteStorageData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productConcreteStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function prepareConcreteProduct(
        ProductViewTransfer $productViewTransfer,
        array $productConcreteStorageData
    ): ProductViewTransfer {
        $productViewTransfer->fromArray($productConcreteStorageData, true);

        return $this->getFactory()
            ->createWishlistItemExpander()
            ->expandProductViewTransferWithProductConcreteData(
                $productViewTransfer,
                $productConcreteStorageData,
                $this->getLocale()
            );
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
     * @param string[] $parameters
     *
     * @return string
     */
    protected function translate(string $key, array $parameters = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $this->getLocale(), $parameters);
    }
}
