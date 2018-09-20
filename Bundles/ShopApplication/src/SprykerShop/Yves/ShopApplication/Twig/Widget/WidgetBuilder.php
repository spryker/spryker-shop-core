<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Widget\WidgetAbstractFactoryInterface;
use Spryker\Yves\Kernel\Widget\WidgetFactoryInterface;

class WidgetBuilder implements WidgetBuilderInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetAbstractFactoryInterface
     */
    protected $widgetAbstractFactory;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    protected $widgetPluginFactory;

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetAbstractFactoryInterface $widgetAbstractFactory
     * @param \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface $widgetPluginFactory
     */
    public function __construct(WidgetAbstractFactoryInterface $widgetAbstractFactory, WidgetFactoryInterface $widgetPluginFactory)
    {
        $this->widgetAbstractFactory = $widgetAbstractFactory;
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

        return $this->widgetAbstractFactory->create($widgetClassName, $arguments);
    }
}
