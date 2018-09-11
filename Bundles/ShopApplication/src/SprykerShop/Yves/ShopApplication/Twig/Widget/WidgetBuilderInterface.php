<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

interface WidgetBuilderInterface
{
    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|\Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface
     */
    public function build(string $widgetClassName, array $arguments);
}
