<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientBridge;

class NewsletterWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_NEWSLETTER = 'CLIENT_NEWSLETTER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        parent::provideDependencies($container);

        $container = $this->addNewsletterClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNewsletterClient(Container $container)
    {
        $container[static::CLIENT_NEWSLETTER] = function (Container $container) {
            return new NewsletterWidgetToNewsletterClientBridge($container->getLocator()->newsletter()->client());
        };

        return $container;
    }
}
