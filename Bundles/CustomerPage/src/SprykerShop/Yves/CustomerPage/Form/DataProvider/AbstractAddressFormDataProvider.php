<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToStoreClientInterface;

abstract class AbstractAddressFormDataProvider
{
    /**
     * @var string
     */
    public const COUNTRY_GLOSSARY_PREFIX = 'countries.iso.';

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToStoreClientInterface $storeClient
     */
    public function __construct(CustomerPageToCustomerClientInterface $customerClient, CustomerPageToStoreClientInterface $storeClient)
    {
        $this->customerClient = $customerClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @return array<string>
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->storeClient->getCurrentStore()->getCountries() as $iso2Code) {
            $countries[$iso2Code] = self::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }
}
