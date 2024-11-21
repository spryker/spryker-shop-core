<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TraceableEventWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\TraceableEventWidget\Dependency\Client\TraceableEventWidgetToSearchHttpClientInterface;

/**
 * @method \SprykerShop\Yves\TraceableEventWidget\TraceableEventWidgetConfig getConfig()
 */
class TraceableEventWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\TraceableEventWidget\Dependency\Client\TraceableEventWidgetToSearchHttpClientInterface
     */
    public function createSearchHttpClient(): TraceableEventWidgetToSearchHttpClientInterface
    {
        return $this->getProvidedDependency(TraceableEventWidgetDependencyProvider::CLIENT_SEARCH_HTTP);
    }
}
