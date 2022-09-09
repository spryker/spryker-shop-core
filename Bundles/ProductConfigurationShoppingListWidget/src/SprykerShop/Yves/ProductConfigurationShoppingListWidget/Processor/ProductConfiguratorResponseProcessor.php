<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_NAME_SHOPPING_LIST_DETAILS
     *
     * @var string
     */
    protected const ROUTE_NAME_SHOPPING_LIST_DETAILS = 'shopping-list/details';

    /**
     * @var string
     */
    protected const PARAMETER_ID_SHOPPING_LIST = 'idShoppingList';

    /**
     * @var \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface
     */
    protected ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface $productConfigurationShoppingListClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected ChainRouterInterface $router;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface $productConfigurationShoppingListClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     */
    public function __construct(
        ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientInterface $productConfigurationShoppingListClient,
        ChainRouterInterface $router
    ) {
        $this->productConfigurationShoppingListClient = $productConfigurationShoppingListClient;
        $this->router = $router;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationShoppingListClient
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, $configuratorResponseData);

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $backUrl = $this->router->generate(
            static::ROUTE_NAME_SHOPPING_LIST_DETAILS,
            [static::PARAMETER_ID_SHOPPING_LIST => $productConfiguratorResponseProcessorResponseTransfer->getIdShoppingList()],
        );

        return $productConfiguratorResponseProcessorResponseTransfer->setBackUrl($backUrl);
    }
}
