<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Widget\WidgetFactoryInterface;
use Spryker\Yves\Kernel\Widget\WidgetPluginFactoryInterface;

class WidgetBuilder implements WidgetBuilderInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    protected $widgetFactory;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetPluginFactoryInterface
     */
    protected $widgetPluginFactory;

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface $widgetFactory
     * @param \Spryker\Yves\Kernel\Widget\WidgetPluginFactoryInterface $widgetPluginFactory
     */
    public function __construct(WidgetFactoryInterface $widgetFactory, WidgetPluginFactoryInterface $widgetPluginFactory)
    {
        $this->widgetFactory = $widgetFactory;
        $this->widgetPluginFactory = $widgetPluginFactory;
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|\Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface
     */
    public function build(string $widgetClassName, array $arguments)
    {
        if (is_subclass_of($widgetClassName, WidgetPluginInterface::class)) {
            return $this->widgetPluginFactory->build($widgetClassName, $arguments);
        }

        return $this->widgetFactory->build($widgetClassName, $arguments);
    }
}
