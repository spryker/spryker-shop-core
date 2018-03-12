<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
abstract class AbstractCompanyController extends AbstractController
{
    public const COMPANY_APPROVED_STATUS = 'approved';
    public const PARAM_PAGE = 'page';
    public const DEFAULT_PAGE = 1;


    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @return bool
     */
    protected function isLoggedInCustomer(): bool
    {
        return $this->getFactory()->getCustomerClient()->isLoggedIn();
    }

    /**
     * @return bool
     */
    protected function isCompanyActive(): bool
    {
        $companyUser = $this->getCompanyUser();

        if ($companyUser === null) {
            return false;
        }

        $companyTransfer = (new CompanyTransfer())->setIdCompany($companyUser->getFkCompany());
        $companyTransfer = $this->getFactory()
            ->getCompanyClient()
            ->getCompanyById($companyTransfer);

        return ($companyTransfer->getIsActive() === true && $companyTransfer->getStatus() === static::COMPANY_APPROVED_STATUS);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCompanyUser(): ?CompanyUserTransfer
    {
        if ($this->isLoggedInCustomer()) {
            $customerTransfer = $this->getFactory()
                ->getCustomerClient()
                ->getCustomer();

            if ($customerTransfer !== null) {
                return $customerTransfer->getCompanyUserTransfer();
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function createPaginationTransfer(Request $request, $limit = 10): PaginationTransfer
    {
        $paginationTransfer = new PaginationTransfer();
        $paginationTransfer->setPage($request->query->getInt(self::PARAM_PAGE, self::DEFAULT_PAGE));
        $paginationTransfer->setMaxPerPage($limit);

        return $paginationTransfer;
    }

    /**
     * @param string $field
     * @param string $direction
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer($field, $direction = 'DESC'): FilterTransfer
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer->setOrderBy($field);
        $filterTransfer->setOrderDirection($direction);

        return $filterTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return void
     */
    protected function processResponseMessages(AbstractTransfer $responseTransfer): void
    {
        if ($responseTransfer->offsetExists('messages')) {
            /** @var null|\Generated\Shared\Transfer\ResponseMessageTransfer[] $responseMessages */
            $responseMessages = $responseTransfer->offsetGet('messages');
            /** @var \Generated\Shared\Transfer\ResponseMessageTransfer $responseMessage */
            foreach ($responseMessages as $responseMessage) {
                $this->addErrorMessage($responseMessage->getText());
            }
        }
    }
}
