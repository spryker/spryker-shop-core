<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryWidget\Dependency\Client;

interface CategoryWidgetToCategoryExporterClientInterface
{
    /**
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale);
}
