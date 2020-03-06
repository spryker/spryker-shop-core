<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientInterface;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;

class OrderSearchFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientInterface $salesClient
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        CustomerPageConfig $customerPageConfig,
        CustomerPageToSalesClientInterface $salesClient,
        CustomerPageToGlossaryStorageClientInterface $glossaryStorageClient,
        Store $store
    ) {
        $this->customerPageConfig = $customerPageConfig;
        $this->salesClient = $salesClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->store = $store;
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    public function getOptions(string $localeName): array
    {
        return [
            OrderSearchForm::OPTION_ORDER_SEARCH_TYPES => $this->getOrderSearchTypes($localeName),
            OrderSearchForm::OPTION_CURRENT_TIMEZONE => $this->getStoreTimezone(),
            OrderSearchForm::OPTION_PER_PAGE => $this->customerPageConfig->getOrderSearchPerPage(),
        ];
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    protected function getOrderSearchTypes(string $localeName): array
    {
        $searchTypes = [];

        foreach ($this->salesClient->getOrderSearchTypes() as $searchType) {
            $searchTypeTitle = $this->glossaryStorageClient->translate(
                $this->generateSearchTypeGlossaryKey($searchType),
                $localeName
            );

            $searchTypes[$searchTypeTitle] = $searchType;
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
