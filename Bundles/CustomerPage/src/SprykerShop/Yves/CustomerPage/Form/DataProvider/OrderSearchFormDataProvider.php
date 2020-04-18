<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;

class OrderSearchFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(CustomerPageConfig $customerPageConfig, Store $store)
    {
        $this->customerPageConfig = $customerPageConfig;
        $this->store = $store;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            OrderSearchForm::OPTION_ORDER_SEARCH_TYPES => $this->getOrderSearchTypes(),
            OrderSearchForm::OPTION_CURRENT_TIMEZONE => $this->getStoreTimezone(),
        ];
    }

    /**
     * @return array
     */
    protected function getOrderSearchTypes(): array
    {
        $searchTypes = [];

        foreach ($this->customerPageConfig->getOrderSearchTypes() as $searchType) {
            $searchTypes[$this->generateSearchTypeGlossaryKey($searchType)] = $searchType;
        }

        return $searchTypes;
    }

    /**
     * @return string|null
     */
    protected function getStoreTimezone(): ?string
    {
        return $this->store->getContexts()['*']['timezone'] ?? null;
    }

    /**
     * @param string $searchType
     *
     * @return string
     */
    protected function generateSearchTypeGlossaryKey(string $searchType): string
    {
        return sprintf('customer.order_history.search_type.%s', $searchType);
    }
}
