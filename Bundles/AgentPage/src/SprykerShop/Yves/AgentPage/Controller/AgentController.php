<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentController extends AbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $viewData = $this->executeIndexAction();

        return $this->view($viewData, [], '@AgentPage/views/overview/overview.twig');
    }

    /**
     * @return array
     */
    protected function executeIndexAction(): array
    {
        return [
            'agent' => $this->getFactory()->getAgentClient()->getAgent(),
        ];
    }
}
