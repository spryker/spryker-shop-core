<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Shared\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 */
class MerchantSwitcherSelectorFormWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->addParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantSwitcherSelectorFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantSwitcherWidget/views/merchant-switcher-selector-form-widget/merchant-switcher-selector-form-widget.twig';
    }

    /**
     * @return void
     */
    protected function addParameters(): void
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->getApplication()['request'];

        $activeMerchantTransfers = $this->getFactory()->createActiveMerchantReader()->getActiveMerchants()->getMerchants();
        $selectedMerchantReference = $request->cookies->get(MerchantSwitcherWidgetConfig::MERCHANT_SELECTOR_COOKIE_IDENTIFIER);

        if (!$selectedMerchantReference) {
            /** @var \Generated\Shared\Transfer\MerchantTransfer $selectedMerchant */
            $selectedMerchant = $activeMerchantTransfers->getIterator()->current();
            $selectedMerchantReference = $selectedMerchant->getMerchantKey();
        }

        $this->addParameter('activeMerchantTransfers', $activeMerchantTransfers);
        $this->addParameter('selectedMerchantReference', $selectedMerchantReference);
    }
}
