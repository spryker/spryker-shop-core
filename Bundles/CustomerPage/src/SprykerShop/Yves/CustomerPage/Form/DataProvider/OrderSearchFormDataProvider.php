<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;

class OrderSearchFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

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
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        CustomerPageConfig $customerPageConfig,
        CustomerPageToGlossaryStorageClientInterface $glossaryStorageClient,
        Store $store
    ) {
        $this->customerPageConfig = $customerPageConfig;
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
            OrderSearchForm::OPTION_ORDER_SEARCH_GROUPS => $this->getOrderSearchGroups($localeName),
            OrderSearchForm::OPTION_CURRENT_TIMEZONE => $this->getStoreTimezone(),
        ];
    }

    /**
     * @param string $localeName
     *
     * @return array
     */
    protected function getOrderSearchGroups(string $localeName): array
    {
        $searchGroups = [];

        foreach ($this->customerPageConfig->getOrderSearchGroups() as $glossaryKey => $searchGroup) {
            $searchGroupTitle = $this->glossaryStorageClient->translate($glossaryKey, $localeName);
            $searchGroups[$searchGroupTitle] = $searchGroup;
        }

        return $searchGroups;
    }

    /**
     * @return string|null
     */
    protected function getStoreTimezone(): ?string
    {
        return $this->store->getContexts()['*']['timezone'] ?? null;
    }
}
