<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client\MerchantRegistrationRequestPageToStoreClientInterface;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Form\MerchantRegistrationRequestPageForm;

class MerchantRegistrationRequestPageFormDataProvider
{
    /**
     * @var string
     */
    public const COUNTRY_GLOSSARY_PREFIX = 'countries.iso.';

    public function __construct(protected MerchantRegistrationRequestPageToStoreClientInterface $storeClient)
    {
    }

    public function getData(): MerchantRegistrationRequestTransfer
    {
        return new MerchantRegistrationRequestTransfer();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            MerchantRegistrationRequestPageForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->storeClient->getCurrentStore()->getCountries() as $iso2Code) {
            $countries[$iso2Code] = static::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }
}
