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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $localizedTermsAndConditionsPageLinks = $this->checkoutPageConfig->getLocalizedTermsAndConditionsPageLinks();
        $currentLocale = $this->localeClient->getCurrentLocale();

        if (!isset($localizedTermsAndConditionsPageLinks[$currentLocale])) {
            return [
                SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL => static::GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS,
            ];
        }

        return [
            SummaryForm::OPTION_ACCEPT_TERM_AND_CONDITIONS_LABEL => $this->generateOptionAcceptTermAndConditionsLabel(
                $localizedTermsAndConditionsPageLinks,
                $currentLocale
            ),
        ];
    }

    /**
     * @param string[] $localizedTermsAndConditionsPageLinks
     * @param string $currentLocale
     *
     * @return string
     */
    protected function generateOptionAcceptTermAndConditionsLabel(array $localizedTermsAndConditionsPageLinks, string $currentLocale): string
    {
        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $localizedTermsAndConditionsPageLinks[$currentLocale],
            $this->glossaryStorageClient->translate(static::GLOSSARY_KEY_ACCEPT_TERM_AND_CONDITIONS, $this->localeClient->getCurrentLocale())
        );
    }
}
