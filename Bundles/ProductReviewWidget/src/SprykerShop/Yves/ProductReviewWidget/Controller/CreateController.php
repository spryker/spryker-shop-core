<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Controller;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class CreateController extends AbstractController
{
    public const ERROR_MESSAGE_NO_CUSTOMER = 'Only customers can use this feature. Please log in.';

    public const REQUEST_HEADER_REFERER = 'referer';
    public const URL_MAIN = '/';

    protected const GLOSSARY_KEY_SUCCESS_PRODUCT_REVIEW_SUBMITTED = 'product_review.submit.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->executeIndexAction($request);

        return $this->redirectResponseExternal($this->getRefererUrl($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeIndexAction(Request $request): void
    {
        $idProductAbstract = $request->attributes->get('idProductAbstract');
        $form = $this->getFactory()
            ->createProductReviewForm($idProductAbstract)
            ->handleRequest($request);

        if (!$form->isSubmitted()) {
            return;
        }

        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_NO_CUSTOMER);

            return;
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addErrorMessage($error->getMessage());
            }

            return;
        }

        $productReviewRequestTransfer = $this->getProductReviewFormData($form)
            ->setCustomerReference($customer->getCustomerReference())
            ->setLocaleName($this->getLocale());

        $productReviewResponseTransfer = $this->getFactory()
            ->getProductReviewClient()
            ->submitCustomerReview($productReviewRequestTransfer);

        if ($productReviewResponseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($productReviewResponseTransfer->getErrors()[0]->getMessage());

            return;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESS_PRODUCT_REVIEW_SUBMITTED);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductReviewRequestTransfer
     */
    protected function getProductReviewFormData(FormInterface $form): ProductReviewRequestTransfer
    {
        return $form->getData();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getRefererUrl(Request $request): string
    {
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            return $request->headers->get(static::REQUEST_HEADER_REFERER);
        }

        return static::URL_MAIN;
    }
}
