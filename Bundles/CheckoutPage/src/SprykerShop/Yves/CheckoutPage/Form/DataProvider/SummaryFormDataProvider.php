<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;
use SprykerShop\Yves\CheckoutPage\Form\Steps\SummaryForm;

class SummaryFormDataProvider implements StepEngineFormDataProviderInterface
{
    protected const GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS = 'page.checkout.summary.accept_terms_and_conditions';

    protected const PATTERN_HTML_LINK = '<a href="%s" target="_blank">%s</a>';

    /**
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface $localeClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        CheckoutPageConfig $checkoutPageConfig,
        CheckoutPageToLocaleClientInterface $localeClient,
        CheckoutPageToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->checkoutPageConfig = $checkoutPageConfig;
        $this->localeClient = $localeClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, string>
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $localizedTermsAndConditionsPageLinks = $this->checkoutPageConfig->getLocalizedTermsAndConditionsPageLinks();
        $currentLocale = $this->localeClient->getCurrentLocale();

        if (array_key_exists($currentLocale, $localizedTermsAndConditionsPageLinks)) {
            return [
                SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL => sprintf(
                    static::PATTERN_HTML_LINK,
                    $localizedTermsAndConditionsPageLinks[$currentLocale],
                    $this->translate(static::GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS)
                ),
            ];
        }

        return [
            SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL => static::GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS,
        ];
    }

    /**
     * @param string $translationKey
     *
     * @return string
     */
    protected function translate(string $translationKey): string
    {
        return $this->glossaryStorageClient->translate($translationKey, $this->localeClient->getCurrentLocale());
    }
}
