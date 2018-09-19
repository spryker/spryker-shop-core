<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class AgentControlBarWidget extends AbstractWidget
{
    public function __construct()
    {
        $isLoggedIn = $this->getFactory()->getAgentClient()->isLoggedIn();

        $this
            ->addParameter('isLoggedIn', $isLoggedIn)
            ->addParameter('agent', $isLoggedIn ? $this->getFactory()->getAgentClient()->getAgent() : null)
            ->addParameter('customer', $this->getFactory()->getCustomerClient()->getCustomer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'AgentControlBarWidget';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AgentWidget/views/agent-widget/agent-widget.twig';
    }
}
