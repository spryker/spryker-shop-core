<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HomePage\Dependency\Plugin\FeaturedProductWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface FeaturedProductListWidgetInterface extends WidgetPluginInterface
{

    public const NAME = 'FeaturedProductListWidgetPlugin';

    /**
     * @param int $limit
     *
     * @return void
     */
    public function initialize(int $limit): void;

}
