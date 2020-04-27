<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget\Plugin\Twig;

use Spryker\Shared\Chart\Dependency\Plugin\ChartLayoutablePluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;
use Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ChartWidget\ChartWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ChartWidget\ChartWidgetConfig getConfig()
 */
abstract class AbstractTwigChartPlugin extends AbstractPlugin implements TwigChartFunctionPluginInterface
{
    public const TWIG_FUNCTION_NAME = 'chart';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getChartFunctions(): array
    {
        return [
            new TwigFunction(
                static::TWIG_FUNCTION_NAME,
                [$this, 'renderChart'],
                $this->getDefaultTwigOptions()
            ),
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return string
     */
    public function renderChart(Environment $twig, $chartPluginName, $dataIdentifier = null): string
    {
        $context = $this->getChartContext($chartPluginName, $dataIdentifier);
        $rendered = $twig->render($this->getTemplateName(), $context);

        return $rendered;
    }

    /**
     * @return string
     */
    abstract protected function getTemplateName(): string;

    /**
     * @phpstan-return array<mixed>
     *
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return array
     */
    protected function getChartContext($chartPluginName, $dataIdentifier): array
    {
        $chartPlugin = $this->getChartPluginByName($chartPluginName);

        $context = [
            'data' => $chartPlugin->getChartData($dataIdentifier),
            'layout' => $this->getConfig()->getDefaultChartLayout(),
        ];
        if ($chartPlugin instanceof ChartLayoutablePluginInterface) {
            $context['layout'] = $chartPlugin->getChartLayout();
        }

        return $context;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @return array
     */
    protected function getDefaultTwigOptions(): array
    {
        return [
            'is_safe' => ['html'],
            'needs_environment' => true,
        ];
    }

    /**
     * @param string $pluginName
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    protected function getChartPluginByName($pluginName): ChartPluginInterface
    {
        return $this->getFactory()
            ->createChartPluginCollection()
            ->getChartPluginByName($pluginName);
    }
}
