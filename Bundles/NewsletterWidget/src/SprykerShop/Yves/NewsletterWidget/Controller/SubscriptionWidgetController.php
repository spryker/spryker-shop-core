<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Controller;

use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 */
class SubscriptionWidgetController extends AbstractController
{
    protected const MESSAGE_SUBSCRIPTION_SUCCESS = 'newsletter.subscription.success';
    protected const MESSAGE_SUBSCRIPTION_ERROR = 'newsletter.subscription.error';

    protected const REQUEST_HEADER_REFERER = 'referer';
    protected const URL_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Spryker\Yves\Kernel\View\View
     */
    public function subscribeAction(Request $request)
    {
        $subscriptionForm = $this
            ->getFactory()
            ->getNewsletterSubscriptionForm();

        $parentRequest = $this->getApplication()['request_stack']->getParentRequest();
        $redirectUrl = $this->getRefererUrl($request);

        if ($parentRequest !== null) {
            $request = $parentRequest;
        }

        $subscriptionForm->handleRequest($request);

        if (!$subscriptionForm->isSubmitted()) {
            return $this->view(
                [
                    'newsletterSubscriptionForm' => $subscriptionForm->createView(),
                ],
                [],
                '@NewsletterWidget/views/subscription-form/subscription-form.twig'
            );
        }

        if (!$subscriptionForm->isValid()) {
            foreach ($subscriptionForm->getErrors(true) as $errorObject) {
                $this->addErrorMessage($errorObject->getMessage());
            }

            return $this->redirectResponseExternal($redirectUrl);
        }

        $emailValue = $subscriptionForm
            ->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)
            ->getData();
        $subscriptionResult = $this->getFactory()
            ->createDoubleOptInSubscriptionRequestHandler()
            ->subscribe($emailValue);

        if (!$subscriptionResult) {
            $this->addErrorMessage(static::MESSAGE_SUBSCRIPTION_ERROR);

            return $this->redirectResponseExternal($redirectUrl);
        }

        if (!$subscriptionResult->getIsSuccess()) {
            $error = $subscriptionResult->getErrorMessage();
            $this->addErrorMessage($error);

            return $this->redirectResponseExternal($redirectUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_SUBSCRIPTION_SUCCESS);

        return $this->redirectResponseExternal($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|string
     */
    protected function getRefererUrl(Request $request)
    {
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            return $request->headers->get(static::REQUEST_HEADER_REFERER);
        }

        return static::URL_CUSTOMER_OVERVIEW;
    }
}
