<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface;

/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 3/2/18
 * Time: 22:58
 */

class SearchFieldChoicesDataProvider implements SearchFieldChoicesDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryClientInterface
     */
    protected $glossaryClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryClientInterface $glossaryClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface $localeClient
     */
    public function __construct(
        QuickOrderPageToGlossaryClientInterface $glossaryClient,
        QuickOrderPageToLocaleClientInterface $localeClient
    ) {
        $this->glossaryClient = $glossaryClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @return array
     */
    public function getChoices(): array
    {
        return [
            $this->glossaryClient->translate('SKU / Name', $this->localeClient->getCurrentLocale()) => '',
        ];
    }
}
