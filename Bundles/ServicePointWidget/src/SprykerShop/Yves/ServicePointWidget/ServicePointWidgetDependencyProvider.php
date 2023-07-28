<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientBridge;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointStorageClientBridge;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig getConfig()
 */
class ServicePointWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_SEARCH = 'CLIENT_SERVICE_POINT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_STORAGE = 'CLIENT_SERVICE_POINT_STORAGE';

    /**
     * @var string
     */
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addServicePointSearchClient($container);
        $container = $this->addServicePointStorageClient($container);
        $container = $this->addTwigService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addServicePointSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_SEARCH, function (Container $container) {
            return new ServicePointWidgetToServicePointSearchClientBridge(
                $container->getLocator()->servicePointSearch()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addServicePointStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_STORAGE, function (Container $container) {
            return new ServicePointWidgetToServicePointStorageClientBridge($container->getLocator()->servicePointStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }
}
