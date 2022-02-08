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
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const ERROR_MESSAGE_NO_CUSTOMER = 'Only customers can use this feature. Please log in.';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SUCCESS_MESSAGE = 'Review was submitted';

    /**
     * @var string
     */
    public const REQUEST_HEADER_REFERER = 'referer';

    /**
     * @var string
     */
    public const URL_MAIN = '/';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SUCCESS_PRODUCT_REVIEW_SUBMITTED = 'product_review.submit.success';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_NO_CUSTOMER = 'product_review.error.no_customer';

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
            $this->addErrorMessage(static::GLOSSARY_KEY_ERROR_NO_CUSTOMER);

            return;
        }

        if (!$form->isValid()) {
            /** @var \Symfony\Component\Form\FormError $errorObject */
            foreach ($form->getErrors(true) as $errorObject) {
                $this->addErrorMessage($errorObject->getMessage());
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
            /** @var array<\Generated\Shared\Transfer\ProductReviewErrorTransfer> $errors */
            $errors = $productReviewResponseTransfer->getErrors();
            $this->addErrorMessage($errors[0]->getMessage());

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
