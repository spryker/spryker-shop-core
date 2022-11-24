<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface;
use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderItemEmbeddedForm;

class QuickOrderItemEmbeddedFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface $localeClient
     */
    public function __construct(QuickOrderPageToLocaleClientInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            QuickOrderItemEmbeddedForm::OPTION_LOCALE => $this->localeClient->getCurrentLocale(),
        ];
    }
}
