<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use Spryker\Yves\Kernel\Widget\WidgetFactory;
use Spryker\Yves\Kernel\Widget\WidgetFactoryInterface;

class ShopLayoutFactory extends AbstractFactory
{

    /**
     * @return WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry($this->getApplication());
    }

    /**
     * @return WidgetFactoryInterface
     */
    public function createWidgetFactory()
    {
        return new WidgetFactory();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication()
    {
        return (new Pimple())->getApplication();
    }
}
