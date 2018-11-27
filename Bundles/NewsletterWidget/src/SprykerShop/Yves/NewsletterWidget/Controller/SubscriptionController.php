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
class SubscriptionController extends AbstractController
{
    protected const MESSAGE_SUBSCRIPTION_SUCCESS = 'newsletter.subscription.success';
    protected const MESSAGE_SUBSCRIPTION_ERROR = 'newsletter.subscription.error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function subscribeAction(Request $request)
    {
        $subscriptionForm = $this
            ->getFactory()
            ->getNewsletterSubscriptionForm();

        $parentRequest = $this->getApplication()['request_stack']->getParentRequest();

        if ($parentRequest !== null) {
            $request = $parentRequest;
        }

        $subscriptionForm->handleRequest($request);

        $redirectUrl = $this->getFactory()
            ->createUrlGenerator()
            ->getMainPageUrlWithLocale($this->getLocale());

        if (!$subscriptionForm->isSubmitted()) {
            return $this->redirectResponseInternal($redirectUrl);
        }

        if (!$subscriptionForm->isValid()) {
            foreach ($subscriptionForm->getErrors(true) as $errorObject) {
                $this->addErrorMessage($errorObject->getMessage());
            }

            return $this->redirectResponseInternal($redirectUrl);
        }

        $emailValue = $subscriptionForm
            ->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)
            ->getData();
        $subscriptionResult = $this->getFactory()
            ->createSubscriber()
            ->subscribe($emailValue);

        if (!$subscriptionResult) {
            $this->addErrorMessage(static::MESSAGE_SUBSCRIPTION_ERROR);

            return $this->redirectResponseInternal($redirectUrl);
        }

        if (!$subscriptionResult->getIsSuccess()) {
            $error = $subscriptionResult->getErrorMessage();
            $this->addErrorMessage($error);

            return $this->redirectResponseInternal($redirectUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_SUBSCRIPTION_SUCCESS);

        return $this->redirectResponseInternal($redirectUrl);
    }
}
