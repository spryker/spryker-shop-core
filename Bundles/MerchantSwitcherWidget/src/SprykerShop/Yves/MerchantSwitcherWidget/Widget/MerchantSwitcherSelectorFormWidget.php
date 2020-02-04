<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherSelectorFormWidget extends AbstractWidget
{
    public function __construct()
    {
        if (!$this->getConfig()->isMerchantSwitcherEnabled()) {
            return;
        }

        $this->addMerchantTransfersParameter();
        $this->addSelectedMerchantReferenceParameter();
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
    protected function addMerchantTransfersParameter(): void
    {
        $merchantTransfers = $this->getFactory()
            ->createMerchantReader()
            ->getActiveMerchants()
            ->getMerchants();

        $this->addParameter('merchantTransfers', $merchantTransfers);
    }

    /**
     * @return void
     */
    protected function addSelectedMerchantReferenceParameter(): void
    {
        $selectedMerchantReference = $this->getFactory()
            ->createMerchantReader()
            ->getSelectedMerchantReference();

        $this->addParameter('selectedMerchantReference', $selectedMerchantReference);
    }
}
