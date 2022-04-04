<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSearchWidget\Form\DataProvider;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use SprykerShop\Yves\MerchantSearchWidget\Dependency\Client\MerchantSearchWidgetToMerchantSearchClientInterface;

class MerchantsChoiceFormDataProvider
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ALL_MERCHANTS = 'merchant_search_widget.all_merchants';

    /**
     * @uses \SprykerShop\Yves\MerchantSearchWidget\Form\MerchantsChoiceForm::OPTION_MERCHANTS
     *
     * @var string
     */
    protected const OPTION_MERCHANTS = 'merchants';

    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter\MerchantSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const MERCHANT_SEARCH_COLLECTION = 'MerchantSearchCollection';

    /**
     * @var \SprykerShop\Yves\MerchantSearchWidget\Dependency\Client\MerchantSearchWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @param \SprykerShop\Yves\MerchantSearchWidget\Dependency\Client\MerchantSearchWidgetToMerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct(MerchantSearchWidgetToMerchantSearchClientInterface $merchantSearchClient)
    {
        $this->merchantSearchClient = $merchantSearchClient;
    }

    /**
     * @return array<string, array>
     */
    public function getOptions(): array
    {
        $merchantSearchRequestTransfer = new MerchantSearchRequestTransfer();
        $merchantSearchCollectionTransfer = $this->merchantSearchClient
            ->search($merchantSearchRequestTransfer)[static::MERCHANT_SEARCH_COLLECTION];
        $merchantChoice = [static::GLOSSARY_KEY_ALL_MERCHANTS => ''];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantChoice[$merchantSearchTransfer->getName()] = $merchantSearchTransfer->getMerchantReference();
        }

        return [static::OPTION_MERCHANTS => $merchantChoice];
    }
}
