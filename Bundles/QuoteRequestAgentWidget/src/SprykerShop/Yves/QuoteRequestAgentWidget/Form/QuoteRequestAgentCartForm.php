<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetConfig getConfig()
 */
class QuoteRequestAgentCartForm extends AbstractType
{
    public const SUBMIT_BUTTON_SAVE = 'save';
    public const SUBMIT_BUTTON_SAVE_AND_BACK = 'saveAndBack';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = '/agent/quote-request/cart/save';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction(QuoteRequestAgentWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART);
    }
}
