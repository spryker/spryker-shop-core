<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface;

class SearchFieldChoicesDataProvider implements SearchFieldChoicesDataProviderInterface
{
    /**
     * @return array
     */
    public function getChoices(): array
    {
        return [
            'quick-order.search-field.sku-name' => '',
        ];
    }
}
