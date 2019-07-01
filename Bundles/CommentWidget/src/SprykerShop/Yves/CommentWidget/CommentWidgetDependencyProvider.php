<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientBridge;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCustomerClientBridge;

class CommentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMMENT = 'CLIENT_COMMENT';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    public const PLUGINS_COMMENT_THREAD_AFTER_OPERATION_STRATEGY = 'PLUGINS_COMMENT_THREAD_AFTER_OPERATION_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCommentClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCommentThreadAfterOperationStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCommentClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMMENT, function (Container $container) {
            return new CommentWidgetToCommentClientBridge($container->getLocator()->comment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new CommentWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCommentThreadAfterOperationStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMMENT_THREAD_AFTER_OPERATION_STRATEGY, function () {
            return $this->getCommentThreadAfterOperationStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface[]
     */
    protected function getCommentThreadAfterOperationStrategyPlugins(): array
    {
        return [];
    }
}
