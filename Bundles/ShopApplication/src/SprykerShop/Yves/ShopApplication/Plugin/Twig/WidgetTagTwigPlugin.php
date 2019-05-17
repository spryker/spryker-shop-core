<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Twig;

use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 */
class WidgetTagTwigPlugin extends AbstractTwigExtensionPlugin
{
    protected const TWIG_FUNCTION_NAME_FIND_WIDGET = 'findWidget';

    /**
     * @return array
     */
    public function getTokenParsers(): array
    {
        return [
            $this->getFactory()->createWidgetTagTwigTokenParser(),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                static::TWIG_FUNCTION_NAME_FIND_WIDGET,
                function (string $widgetName, array $arguments = []) {
                    $widget = $this->openWidgetContext($widgetName, $arguments);

                    if (!$widget) {
                        return null;
                    }

                    $this->closeWidgetContext();

                    return $widget;
                },
                [
                    'needs_context' => false,
                ]
            ),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|string|null $widgetExpression
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|null
     */
    public function openWidgetContext($widgetExpression, array $arguments = [])
    {
        return $this->getFactory()->createWidgetTagService()->openWidgetContext($widgetExpression, $arguments);
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface $widget
     * @param string|null $templatePath
     *
     * @return string
     */
    public function getTemplatePath($widget, ?string $templatePath = null): string
    {
        return $this->getFactory()->createWidgetTagService()->getTemplatePath($widget, $templatePath);
    }

    /**
     * @return void
     */
    public function closeWidgetContext(): void
    {
        $this->getFactory()->createWidgetTagService()->closeWidgetContext();
    }
}
