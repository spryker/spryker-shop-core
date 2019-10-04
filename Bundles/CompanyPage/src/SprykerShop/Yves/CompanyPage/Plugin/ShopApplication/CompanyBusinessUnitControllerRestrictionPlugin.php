<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\ShopApplication;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController;
use SprykerShop\Yves\CompanyPage\Exception\CustomerAccessDeniedException;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyBusinessUnitControllerRestrictionPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController::REQUEST_PARAM_ID
     */
    protected const REQUEST_PARAM_COMPANY_BUSINESS_UNIT_ID = 'id';

    /**
     * @uses \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController::updateAction()
     * @uses \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController::deleteAction()
     * @uses \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController::confirmDeleteAction()
     */
    protected const DENIED_ACTIONS = [
        'updateAction',
        'deleteAction',
        'confirmDeleteAction',
    ];

    protected const GLOSSARY_KEY_COMPANY_PAGE_RESTRICTED = 'company_page.company_business_unit_restricted_message';

    /**
     * {@inheritDoc}
     * - Verifies if customer could perform a BusinessUnitController "denied action" for a given business unit.
     * - Throws exception if customer has no permissions for such company business unit.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        if (!$this->isEventShouldBeHandled($event)) {
            return;
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer && $customerTransfer->getCompanyUserTransfer()) {
            $request = $event->getRequest();
            $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_COMPANY_BUSINESS_UNIT_ID);
            $companyBusinessUnitTransfer = $this->getCompanyBusinessUnitTransfer($idCompanyBusinessUnit);

            if ($companyBusinessUnitTransfer->getFkCompany() === $customerTransfer->getCompanyUserTransfer()->getFkCompany()) {
                return;
            }
        }

        throw new CustomerAccessDeniedException(static::GLOSSARY_KEY_COMPANY_PAGE_RESTRICTED);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return bool
     */
    protected function isEventShouldBeHandled(FilterControllerEvent $event): bool
    {
        $eventController = $event->getController();

        if (!is_array($eventController)) {
            return false;
        }

        [$controllerInstance, $actionName] = $eventController;

        if (!$controllerInstance instanceof BusinessUnitController) {
            return false;
        }

        if (!in_array($actionName, static::DENIED_ACTIONS)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function getCompanyBusinessUnitTransfer(int $idCompanyBusinessUnit): CompanyBusinessUnitTransfer
    {
        return $this->getFactory()->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
            );
    }
}
