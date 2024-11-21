<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TraceableEventWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\TraceableEventWidget\Dependency\Client\TraceableEventWidgetToSearchHttpClientBridge;

class TraceableEventWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SEARCH_HTTP = 'CLIENT_SEARCH_HTTP';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSearchHttpClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchHttpClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH_HTTP, function (Container $container) {
            return new TraceableEventWidgetToSearchHttpClientBridge(
                $container->getLocator()->searchHttp()->client(),
            );
        });

        return $container;
    }
}
