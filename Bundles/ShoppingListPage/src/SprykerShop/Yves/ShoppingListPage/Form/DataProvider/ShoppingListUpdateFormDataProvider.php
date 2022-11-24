<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToLocaleClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListUpdateForm;

class ShoppingListUpdateFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToLocaleClientInterface
     */
    protected ShoppingListPageToLocaleClientInterface $localeClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToLocaleClientInterface $localeClient
     */
    public function __construct(ShoppingListPageToLocaleClientInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @return array<string, string>
     */
    public function getOptions(): array
    {
        return [
            ShoppingListUpdateForm::OPTION_LOCALE => $this->localeClient->getCurrentLocale(),
        ];
    }
}
