<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class SubmitController extends AbstractController
{
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $parentRequest = $this->getParentRequest();
        $idProductAbstract = $request->attributes->get('idProductAbstract');

        $customer = $this->getFactory()->getCustomerClient()->getCustomer();
        $productReviewForm = $this->getFactory()
            ->createProductReviewForm($idProductAbstract)
            ->handleRequest($parentRequest);
        $isFormEmpty = !$productReviewForm->isSubmitted();
        $isReviewPosted = $this->processProductReviewForm($productReviewForm, $customer);

        if ($isReviewPosted) {
            $productReviewForm = $this->getFactory()->createProductReviewForm($idProductAbstract);
        }

        $data = [
            'hideForm' => $isFormEmpty || $isReviewPosted,
            'form' => $productReviewForm->createView(),
            'showSuccess' => $isReviewPosted,
        ];

        return $this->view($data, [], '@ProductReviewWidget/views/review-create/review-create.twig');
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
            $form->addError(new FormError('Only customers can use this feature. Please log in.'));
        }

        if (!$form->isValid()) {
            return false;
        }

        $productReviewResponseTransfer = $this->getFactory()->getProductReviewClient()->submitCustomerReview(
            $this->getProductReviewFormData($form)
                    ->setCustomerReference($customerReference)
                    ->setLocaleName($this->getLocale())
        );

        if ($productReviewResponseTransfer->getIsSuccess()) {
            return true;
        }

        $form->addError(new FormError($productReviewResponseTransfer->getErrors()[0]->getMessage()));

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
