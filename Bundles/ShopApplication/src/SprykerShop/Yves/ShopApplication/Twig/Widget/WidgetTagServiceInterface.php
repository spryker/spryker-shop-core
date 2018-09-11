<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

interface WidgetTagServiceInterface
{
    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|string|null $widgetExpression
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|null
     */
    public function openWidgetContext($widgetExpression, array $arguments = []);

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface $widget
     * @param string|null $templatePath
     *
     * @return string
     */
    public function getTemplatePath($widget, ?string $templatePath = null): string;

    /**
     * @return void
     */
    public function closeWidgetContext(): void;
}
