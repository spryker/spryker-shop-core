<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;

interface WidgetTagServiceInterface
{
    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|string|null $widgetExpression
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|null
     */
    public function openWidgetContext($widgetExpression, array $arguments = []): ?WidgetInterface;

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface $widget
     * @param string|null $templatePath
     *
     * @return string
     */
    public function getTemplatePath(WidgetInterface $widget, ?string $templatePath = null): string;

    /**
     * @return void
     */
    public function closeWidgetContext(): void;
}
