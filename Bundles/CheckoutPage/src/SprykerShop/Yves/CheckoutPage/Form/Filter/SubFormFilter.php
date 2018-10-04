<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Filter;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;

class SubFormFilter implements SubFormFilterInterface
{
    /**
     * @var \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected $subFormFilterPlugins;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[] $subFormFilterPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        array $subFormFilterPlugins,
        CheckoutPageToQuoteClientInterface $quoteClient
    ) {
        $this->subFormFilterPlugins = $subFormFilterPlugins;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPlugins
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function filterFormsCollection(
        SubFormPluginCollection $subFormPlugins
    )
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        $filteredSubFormPlugins = clone $subFormPlugins;

        foreach ($this->subFormFilterPlugins as $subFormFilterPlugin) {
            $filteredSubFormPlugins = $this->applyFilter($filteredSubFormPlugins, $subFormFilterPlugin, $quoteTransfer);
        }

        return $filteredSubFormPlugins;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPluginCollection
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface $subFormFilterPlugin
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function applyFilter(
        SubFormPluginCollection $subFormPluginCollection,
        SubFormFilterPluginInterface $subFormFilterPlugin,
        QuoteTransfer $quoteTransfer
    ) {
        $validFormNames = $subFormFilterPlugin->getValidFormNames($quoteTransfer);

        foreach ($subFormPluginCollection as $key => $subFormPlugin) {
            $subFormName = $subFormPlugin->createSubForm()->getName();

            if (!in_array($subFormName, $validFormNames)) {
                unset($subFormPluginCollection[$key]);
            }
        }

        return $subFormPluginCollection;
    }
}
