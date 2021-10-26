<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetConfig getConfig()
 */
class QuoteRequestAgentCartForm extends AbstractType
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
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Router\QuoteRequestAgentWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SAVE_CART} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART = '/agent/quote-request/cart/save';
}
