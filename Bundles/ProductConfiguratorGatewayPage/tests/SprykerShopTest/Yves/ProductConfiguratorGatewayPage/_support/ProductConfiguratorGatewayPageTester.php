<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ProductConfiguratorGatewayPage;

use Codeception\Actor;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductDetailPageGatewayBackUrlResolverStrategyPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageDependencyProvider;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfiguratorGatewayPageTester extends Actor
{
    use _generated\ProductConfiguratorGatewayPageTesterActions;

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PROTOCOL
     *
     * @deprecated Use {@link \SprykerShopTest\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageTester::REDIS_SCHEME} instead.
     */
    protected const REDIS_PROTOCOL = 'STORAGE_REDIS:STORAGE_REDIS_PROTOCOL';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_SCHEME
     */
    protected const REDIS_SCHEME = 'STORAGE_REDIS:STORAGE_REDIS_SCHEME';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST
     */
    protected const REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT
     */
    protected const REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE
     */
    protected const REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD
     */
    protected const REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * @return void
     */
    public function setupStorageRedisConfig(): void
    {
        $this->setConfig(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(static::REDIS_SCHEME, false) ?: Config::get(static::REDIS_PROTOCOL));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PORT, Config::get(static::REDIS_PORT));
        $this->setConfig(StorageConstants::STORAGE_REDIS_HOST, Config::get(static::REDIS_HOST));
        $this->setConfig(StorageConstants::STORAGE_REDIS_DATABASE, Config::get(static::REDIS_DATABASE));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PASSWORD, Config::get(static::REDIS_PASSWORD));
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory $productConfiguratorGatewayPageFactoryMock
     *
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductDetailPageGatewayBackUrlResolverStrategyPlugin
     */
    public function getProductDetailPageGatewayBackUrlResolverStrategyPluginMock(
        MockObject $productConfiguratorGatewayPageFactoryMock
    ): ProductDetailPageGatewayBackUrlResolverStrategyPlugin {
        $container = new Container();
        $productConfiguratorGatewayDependencyProvider = new ProductConfiguratorGatewayPageDependencyProvider();
        $productConfiguratorGatewayDependencyProvider->provideDependencies($container);

        $productConfiguratorGatewayPageFactoryMock->setContainer($container);

        $productDetailPageGatewayBackUrlResolverStrategyPlugin = $this->getProductDetailPageGatewayBackUrlResolverStrategyPlugin();
        $productDetailPageGatewayBackUrlResolverStrategyPlugin->setFactory($productConfiguratorGatewayPageFactoryMock);

        return $productDetailPageGatewayBackUrlResolverStrategyPlugin;
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductDetailPageGatewayBackUrlResolverStrategyPlugin
     */
    public function getProductDetailPageGatewayBackUrlResolverStrategyPlugin(): ProductDetailPageGatewayBackUrlResolverStrategyPlugin
    {
        return new ProductDetailPageGatewayBackUrlResolverStrategyPlugin();
    }
}
