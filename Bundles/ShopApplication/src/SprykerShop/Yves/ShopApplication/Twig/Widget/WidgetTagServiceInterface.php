<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Twig_Environment;

interface WidgetTagServiceInterface
{
    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return bool
     */
    public function openWidgetContext(Twig_Environment $twig, string $widgetName, array $arguments = []): bool;

    /**
     * @param null|string $templatePath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getTemplatePath(?string $templatePath = null): string;

    /**
     * @return void
     */
    public function closeWidgetContext(): void;

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function getCurrentWidget(): WidgetContainerInterface;
}
