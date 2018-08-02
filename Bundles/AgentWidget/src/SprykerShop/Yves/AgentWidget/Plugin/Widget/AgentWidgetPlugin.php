<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Plugin\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\AgentWidget\AgentWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class AgentWidgetPlugin extends AbstractWidgetPlugin implements AgentWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this
            ->addParameter('isLoggedIn', $this->getFactory()->getAgentClient()->isLoggedIn())
            ->addParameter('agent', $this->getFactory()->getAgentClient()->findLoggedInAgent());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@AgentWidget/views/agent-widget/agent-widget.twig';
    }
}
