<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class SubmitController extends AbstractController
{
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE;
    protected const ERROR_MESSAGE_NO_CUSTOMER = 'Only customers can use this feature. Please log in.';
    protected const SUCCESS_MESSAGE = 'Review was submitted';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return $this->executeIndexAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request): RedirectResponse
    {
        $idProductAbstract = $request->attributes->get('idProductAbstract');
        $abstractProductData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductAbstractStorageData(
                $idProductAbstract,
                $this->getLocale()
            );

        $customer = $this->getFactory()->getCustomerClient()->getCustomer();
        $productReviewForm = $this->getFactory()
            ->createProductReviewForm($idProductAbstract)
            ->handleRequest($request);
        $this->processProductReviewForm($productReviewForm, $customer);

        return $this->redirectResponseInternal($abstractProductData['url']);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customer
     *
     * @return bool Returns true if the review was posted
     */
    protected function processProductReviewForm(FormInterface $form, ?CustomerTransfer $customer = null)
    {
        if (!$form->isSubmitted()) {
            return false;
        }

        $customerReference = $customer === null ? null : $customer->getCustomerReference();

        if ($customerReference === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_NO_CUSTOMER);

            return false;
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addErrorMessage($error->getMessage());
            }

            return false;
        }

        $productReviewResponseTransfer = $this->getFactory()->getProductReviewClient()->submitCustomerReview(
            $this->getProductReviewFormData($form)
                    ->setCustomerReference($customerReference)
                    ->setLocaleName($this->getLocale())
        );

        if ($productReviewResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE);

            return true;
        }

        $this->addErrorMessage($productReviewResponseTransfer->getErrors()[0]->getMessage());

        return false;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductReviewRequestTransfer
     */
    protected function getProductReviewFormData(FormInterface $form)
    {
        return $form->getData();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getParentRequest()
    {
        return $this->getApplication()['request_stack']->getParentRequest();
    }
}
