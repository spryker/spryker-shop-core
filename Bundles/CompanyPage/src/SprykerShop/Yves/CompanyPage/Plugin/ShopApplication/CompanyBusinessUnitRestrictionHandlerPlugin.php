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
class CompanyBusinessUnitRestrictionHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * @see \SprykerShop\Yves\CompanyPage\Controller\BusinessUnitController::REQUEST_PARAM_ID
     */
    protected const REQUEST_PARAM_COMPANY_BUSINESS_UNIT_ID = 'id';

    protected const GLOSSARY_KEY_COMPANY_PAGE_RESTRICTED = 'company_page.company_business_unit_restricted_message';
    protected const DENIED_ACTIONS = [
        'updateAction',
        'deleteAction',
        'confirmDeleteAction',
    ];

    /**
     * {@inheritdoc}
     * - Verifies if customer could perform an action with company business unit.
     * - Throws an exception with predefined message if customer have no permissions for such company business unit.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        $eventController = $event->getController();

        if (!is_array($eventController)) {
            return;
        }

        [$controllerInstance, $actionName] = $eventController;

        if (!($controllerInstance instanceof BusinessUnitController) ||
            !in_array($actionName, self::DENIED_ACTIONS)
        ) {
            return;
        }

        $request = $event->getRequest();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (empty($customerTransfer)) {
            return;
        }

        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_COMPANY_BUSINESS_UNIT_ID);

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $companyBusinessUnitTransfer = $this->getFactory()->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        if ($companyBusinessUnitTransfer->getFkCompany() === $customerTransfer->getCompanyUserTransfer()->getFkCompany()) {
            return;
        }

        throw new CustomerAccessDeniedException(static::GLOSSARY_KEY_COMPANY_PAGE_RESTRICTED);
    }
}
