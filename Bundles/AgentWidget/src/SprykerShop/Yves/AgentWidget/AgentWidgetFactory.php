<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientInterface;

class AgentWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientInterface
     */
    public function getAgentClient(): AgentWidgetToAgentClientInterface
    {
        return $this->getProvidedDependency(AgentWidgetDependencyProvider::CLIENT_AGENT);
    }
}
