<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use SubscriptionWidgetController instead
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 */
class SubscriptionController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function subscribeAction(Request $request)
    {
        $viewData = $this->executeSubscribeAction($request);

        return $this->view($viewData, [], '@NewsletterWidget/views/subscription-form/subscription-form.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeSubscribeAction(Request $request): array
    {
        $success = false;
        $error = false;

        $subscriptionForm = $this
            ->getFactory()
            ->getNewsletterSubscriptionForm();

        $parentRequest = $this->getApplication()['request_stack']->getParentRequest();

        if ($parentRequest !== null) {
            $request = $parentRequest;
        }

        $subscriptionForm->handleRequest($request);

        if ($subscriptionForm->isSubmitted() && $subscriptionForm->isValid()) {
            $customerTransfer = (new CustomerTransfer())
                ->setEmail($subscriptionForm->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)->getData());

            $request = $this->createNewsletterSubscriptionRequest($customerTransfer);
            $subscriptionResponse = $this->getFactory()
                ->getNewsletterClient()
                ->subscribeWithDoubleOptIn($request);

            $subscriptionResult = current($subscriptionResponse->getSubscriptionResults());

            if ($subscriptionResult->getIsSuccess()) {
                $subscriptionForm = $this
                    ->getFactory()
                    ->getNewsletterSubscriptionForm();
                $success = 'newsletter.subscription.success';
            }

            if (!$subscriptionResult->getIsSuccess()) {
                $error = $subscriptionResult->getErrorMessage();
            }
        }

        return [
            'newsletterSubscriptionForm' => $subscriptionForm->createView(),
            'error' => $error,
            'success' => $success,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $subscriberKey
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer
     */
    protected function createNewsletterSubscriptionRequest(CustomerTransfer $customerTransfer, $subscriberKey = null)
    {
        $subscriptionRequest = new NewsletterSubscriptionRequestTransfer();

        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriber->setEmail($customerTransfer->getEmail());
        $subscriber->setSubscriberKey($subscriberKey);

        $subscriptionRequest->setNewsletterSubscriber($subscriber);
        $subscriptionRequest->addSubscriptionType((new NewsletterTypeTransfer())
            ->setName(NewsletterConstants::DEFAULT_NEWSLETTER_TYPE));

        return $subscriptionRequest;
    }
}
