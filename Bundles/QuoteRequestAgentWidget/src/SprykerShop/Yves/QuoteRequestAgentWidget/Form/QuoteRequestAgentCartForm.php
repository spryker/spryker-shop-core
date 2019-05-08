<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetConfig getConfig()
 */
class QuoteRequestAgentCartForm extends AbstractType
{
    public const SUBMIT_BUTTON_SAVE = 'save';
    public const SUBMIT_BUTTON_SAVE_AND_BACK = 'saveAndBack';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentWidget\Plugin\Provider\QuoteRequestAgentWidgetControllerProvider::ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART
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
        $builder->setAction(static::ROUTE_QUOTE_REQUEST_AGENT_SAVE_CART);
    }
}
