<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CompanyPage\Controller\AbstractCompanyController;
use SprykerShop\Yves\ShopApplication\Dependency\Plugin\FilterControllerEventSubscriberPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyPageFilterControllerEventSubscriberPlugin extends AbstractPlugin implements FilterControllerEventSubscriberPluginInterface
{
    protected const COMPANY_REDIRECT_URL = '/company/user/select';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function subscribe(FilterControllerEvent $event): void
    {
        list($controllerInstance, $actionName) = $event->getController();

        if ($controllerInstance instanceof AbstractCompanyController
            && $this->getFactory()->getCustomerClient()->getCustomer()
            && $this->getFactory()->getCustomerClient()->getCustomer()->getIsOnBehalf()
            && !$this->getFactory()->getCustomerClient()->getCustomer()->getCompanyUserTransfer()
            && $event->getRequest()->getRequestUri() !== static::COMPANY_REDIRECT_URL
        ) {
            $event->setController(function () {
                return new RedirectResponse(static::COMPANY_REDIRECT_URL);
            });
        }
    }
}
