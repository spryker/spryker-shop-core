<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestForm;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApproveRequestWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $form = $this->createQuoteApprovalRequestForm($quoteTransfer);
        $quoteApprovalStatus = $this->findQuoteApprovalStatusByQuote($quoteTransfer);

        $this->addParameter('limit', $this->getApproverLimitByCurrency($quoteTransfer->getCurrency()->getCode()));
        $this->addParameter('isVisible', $this->isVisible($quoteTransfer));
        $this->addParameter('canSendApprovalRequest', $this->canSendApprovalRequest($quoteApprovalStatus));
        $this->addParameter('form', $form->createView());
        $this->addParameter('quote', $form->getData()->getQuote());
        $this->addParameter('quoteApprovalStatus', $quoteApprovalStatus);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteApproveRequestWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteApprovalWidget/views/quote-approve-request/quote-approve-request.twig';
    }

    /**
     * @param string|null $quoteApprovalStatus
     *
     * @return bool
     */
    protected function canSendApprovalRequest(?string $quoteApprovalStatus)
    {
        return $quoteApprovalStatus === null || $quoteApprovalStatus === QuoteApprovalConfig::STATUS_DECLINED;
    }

    /**
     * @return bool
     */
    protected function isVisible(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->findPlaceOrderPermission() === null) {
            return false;
        }

        return !$this->getFactory()->getPermissionClient()->can(PlaceOrderPermissionPlugin::KEY, $quoteTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    protected function findPlaceOrderPermission(): ?PermissionTransfer
    {
        return $this->getFactory()->getPermissionClient()->findCustomerPermissionByKey(
            PlaceOrderPermissionPlugin::KEY
        );
    }

    /**
     * @param string $currencyCode
     *
     * @return int
     */
    protected function getApproverLimitByCurrency(string $currencyCode): int
    {
        $placeOrderPermission = $this->findPlaceOrderPermission();

        if (!$placeOrderPermission) {
            return 0;
        }

        $configuration = $placeOrderPermission
            ->getConfiguration();

        return $configuration[PlaceOrderPermissionPlugin::FIELD_MULTI_CURRENCY][$currencyCode] ?? 0;
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        return $this->getApplication()['locale'];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|string
     */
    protected function findQuoteApprovalStatusByQuote(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->createQuoteApprovalStatusCalculator()
            ->calculateQuoteStatus($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createQuoteApprovalRequestForm(QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createQuoteApproveRequestForm(
            $quoteTransfer,
            $this->getLocale()
        );
    }
}
