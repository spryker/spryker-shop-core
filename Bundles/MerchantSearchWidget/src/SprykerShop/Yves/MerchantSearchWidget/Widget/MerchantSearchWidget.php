<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSearchWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetConfig getConfig()
 * @method \SprykerShop\Yves\MerchantSearchWidget\MerchantSearchWidgetFactory getFactory()
 */
class MerchantSearchWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->addParameter('merchantForm', $this->getMerchantForm());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantSearchWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantSearchWidget/views/merchant-search/merchant-search.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getMerchantForm(): FormView
    {
        $shopContextTransfer = $this->getFactory()->getShopContext();
        $formOptions = $this->getFactory()->createMerchantsChoiceFormDataProvider()->getOptions();
        $form = $this->getFactory()->createMerchantsChoiceForm($formOptions);

        if ($shopContextTransfer->getMerchantReference()) {
            $form = $this->getFactory()->createMerchantsHiddenForm()->setData([$shopContextTransfer->getMerchantReference()]);
        }

        return $form->createView();
    }
}
