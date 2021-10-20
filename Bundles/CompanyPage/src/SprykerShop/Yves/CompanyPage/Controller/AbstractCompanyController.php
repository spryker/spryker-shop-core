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

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
abstract class AbstractCompanyController extends AbstractController
{
    /**
     * @var string
     */
    public const COMPANY_APPROVED_STATUS = 'approved';

    /**
     * @var string
     */
    public const PARAM_PAGE = 'page';

    /**
     * @var int
     */
    public const DEFAULT_PAGE = 1;

    /**
     * @return bool
     */
    protected function isCompanyActive(): bool
    {
        $companyUser = $this->findCurrentCompanyUserTransfer();

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
     * @deprecated Use {@link \SprykerShop\Yves\CompanyPage\Controller\AbstractCompanyController::findCurrentCompanyUserTransfer()} instead.
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCompanyUser(): ?CompanyUserTransfer
    {
        return $this->findCurrentCompanyUserTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCurrentCompanyUserTransfer(): ?CompanyUserTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$customerTransfer) {
            return null;
        }

        return $customerTransfer->getCompanyUserTransfer();
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
            /** @var array<\Generated\Shared\Transfer\ResponseMessageTransfer> $responseMessages */
            $responseMessages = $responseTransfer->offsetGet('messages');

            foreach ($responseMessages as $responseMessage) {
                $this->addErrorMessage($responseMessage->getText());
            }
        }
    }

    /**
     * @param string $key
     * @param string $locale
     * @param array $params
     *
     * @return string
     */
    protected function getTranslatedMessage(string $key, string $locale, array $params = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $locale, $params);
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return void
     */
    protected function addTranslatedSuccessMessage(string $key, array $params = []): void
    {
        $message = $this->getTranslatedMessage($key, $this->getLocale(), $params);

        $this->addSuccessMessage($message);
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return void
     */
    protected function addTranslatedErrorMessage(string $key, array $params = []): void
    {
        $message = $this->getTranslatedMessage($key, $this->getLocale(), $params);

        $this->addErrorMessage($message);
    }

    /**
     * @param int $idCompany
     *
     * @return bool
     */
    protected function isCurrentCustomerRelatedToCompany(int $idCompany): bool
    {
        $companyUserTransfer = $this->findCurrentCompanyUserTransfer();

        return ($companyUserTransfer !== null && $companyUserTransfer->getFkCompany() === $idCompany);
    }
}
