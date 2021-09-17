<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\TwigBarChartPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\TwigChartPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\TwigLineChartPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\TwigPieChartPlugin;

class ChartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_TWIG_CHART_FUNCTIONS = 'PLUGIN_TWIG_CHART_FUNCTIONS';
    /**
     * @var string
     */
    public const PLUGIN_CHARTS = 'PLUGIN_CHARTS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addTwigChartFunctionPlugins($container);
        $container = $this->addChartPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigChartFunctionPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_TWIG_CHART_FUNCTIONS, function () {
            return $this->getTwigChartFunctionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addChartPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CHARTS, function () {
            return $this->getChartPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface>
     */
    protected function getTwigChartFunctionPlugins(): array
    {
        return [
            new TwigPieChartPlugin(),
            new TwigBarChartPlugin(),
            new TwigLineChartPlugin(),
            new TwigChartPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface>
     */
    protected function getChartPlugins(): array
    {
        return [];
    }
}
