<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Form\DataProvider;

use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToLocaleClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;

class ProductQuickAddFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToLocaleClientInterface $localeClient
     */
    public function __construct(ProductSearchWidgetToLocaleClientInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ProductQuickAddForm::OPTION_LOCALE => $this->localeClient->getCurrentLocale(),
        ];
    }
}
