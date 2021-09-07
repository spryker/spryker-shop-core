<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\QuoteRequestWidget\Plugin\Router\QuoteRequestWidgetRouteProviderPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetConfig getConfig()
 */
class QuoteRequestCartForm extends AbstractType
{
    /**
     * @var string
     */
    public const SUBMIT_BUTTON_SAVE = 'save';
    /**
     * @var string
     */
    public const SUBMIT_BUTTON_SAVE_AND_BACK = 'saveAndBack';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestWidget\Plugin\Router\QuoteRequestWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_SAVE_CART
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_SAVE_CART = '/quote-request/cart/save';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction(QuoteRequestWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_SAVE_CART);
    }
}
