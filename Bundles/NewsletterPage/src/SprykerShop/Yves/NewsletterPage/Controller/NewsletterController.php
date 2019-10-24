<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use SprykerShop\Yves\NewsletterPage\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\NewsletterPage\Plugin\Provider\NewsletterPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\NewsletterPage\NewsletterPageFactory getFactory()
 */
class NewsletterController extends AbstractController
{
    public const MESSAGE_UNSUBSCRIPTION_SUCCESS = 'newsletter.unsubscription.success';
    public const MESSAGE_SUBSCRIPTION_SUCCESS = 'newsletter.subscription.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@NewsletterPage/views/newsletter/newsletter.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $newsletterSubscriptionRequestTransfer = $this->createNewsletterSubscriptionRequest($customerTransfer);

        $dataProvider = $this->getFactory()->createNewsletterSubscriptionFormDataProvider();
        $newsletterSubscriptionForm = $this->getFactory()
            ->getNewsletterSubscriptionFor(
                $dataProvider->getData($newsletterSubscriptionRequestTransfer),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($newsletterSubscriptionForm->isSubmitted() && $newsletterSubscriptionForm->isValid()) {
            $this->processForm($newsletterSubscriptionForm, $newsletterSubscriptionRequestTransfer);

            return $this->redirectResponseInternal(NewsletterPageControllerProvider::ROUTE_CUSTOMER_NEWSLETTER);
        }

        return [
            'customer' => $customerTransfer,
            'form' => $newsletterSubscriptionForm->createView(),
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

    /**
     * @param \Symfony\Component\Form\FormInterface $newsletterSubscriptionForm
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    protected function processForm(FormInterface $newsletterSubscriptionForm, NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $subscribe = (bool)$newsletterSubscriptionForm->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)->getData();

        if ($subscribe === true) {
            $this->processSubscription($newsletterSubscriptionRequestTransfer);

            return;
        }

        $this->processUnsubscription($newsletterSubscriptionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    protected function processSubscription(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $subscriptionResponse = $this->getFactory()
            ->getNewsletterClient()
            ->subscribeWithDoubleOptIn($newsletterSubscriptionRequestTransfer);

        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer $newsletterSubscriptionResultTransfer */
        $newsletterSubscriptionResultTransfer = $subscriptionResponse->getSubscriptionResults()->getIterator()->current();
        if ($newsletterSubscriptionResultTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUBSCRIPTION_SUCCESS);

            return;
        }

        $this->addErrorMessage($newsletterSubscriptionResultTransfer->getErrorMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    protected function processUnsubscription(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $this->getFactory()
            ->getNewsletterClient()
            ->unsubscribe($newsletterSubscriptionRequestTransfer);

        $this->addSuccessMessage(static::MESSAGE_UNSUBSCRIPTION_SUCCESS);
    }
}
