<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CompanyPage\Controller\AbstractCompanyController;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CheckBusinessOnBehalfCompanyUserHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    protected const COMPANY_REDIRECT_ROUTE = 'company/user/select';

    /**
     * {@inheritDoc}
     * - Verifies if customer is logged-in and has isOnBehalf flag without a selected company for current session.
     * - Redirects verified customer to the pre-configured route if the requested page is a company management pages.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        $companySelectUrl = $this->getFactory()->getApplication()->path(static::COMPANY_REDIRECT_ROUTE);
        if ($companySelectUrl === $event->getRequest()->getRequestUri()
            || !$this->isCompanyControllerRequested($event)
        ) {
            return;
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        if ($customerTransfer
            && $customerTransfer->getIsOnBehalf()
            && !$customerTransfer->getCompanyUserTransfer()
        ) {
            $event->setController(function () use ($companySelectUrl) {
                return new RedirectResponse($companySelectUrl);
            });
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return bool
     */
    protected function isCompanyControllerRequested(FilterControllerEvent $event): bool
    {
        $eventController = $event->getController();
        if (!is_array($eventController)) {
            return false;
        }

        [$controllerInstance] = $eventController;

        return $controllerInstance instanceof AbstractCompanyController;
    }
}
