<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConstants;

class QuickOrderPageConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getAllowedSeparators(): array
    {
        return $this->get(QuickOrderPageConstants::ALLOWED_SEPARATORS, [',', ';', ' ']);
    }

    /**
     * @return int
     */
    public function getProductRowsNumber(): int
    {
        return $this->get(QuickOrderPageConstants::PRODUCT_ROWS_NUMBER, 8);
    }

    /**
     * @return int
     */
    public function getSuggestionResultsLimit(): int
    {
        return $this->get(QuickOrderPageConstants::SUGGESTION_RESULTS_LIMIT, 10);
    }


    public function getSearchFieldMapping(): array
    {
        return $this->get(QuickOrderPageConstants::SEARCH_FIELD_MAPPING, [
            'name_sku' => PageIndexMap::FULL_TEXT,
        ]);
    }
}
