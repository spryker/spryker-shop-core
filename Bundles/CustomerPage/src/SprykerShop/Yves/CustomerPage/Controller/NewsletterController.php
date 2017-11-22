<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use SprykerShop\Yves\CustomerPage\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
// TODO: move to NewsletterPage module
class NewsletterController extends AbstractCustomerController
{
    const MESSAGE_UNSUBSCRIPTION_SUCCESS = 'newsletter.unsubscription.success';
    const MESSAGE_UNSUBSCRIPTION_FAILED = 'newsletter.unsubscription.failed';
    const MESSAGE_SUBSCRIPTION_CONFIRMATION_APPROVED = 'newsletter.subscription.confirmation.approved';
    const MESSAGE_SUBSCRIPTION_FAILED = 'newsletter.subscription.failed';
    const MESSAGE_SUBSCRIPTION_SUCCESS = 'newsletter.subscription.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $customerTransfer = $this->getLoggedInCustomerTransfer();

        $newsletterForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createNewsletterSubscriptionForm()
            ->handleRequest($request);

        if ($newsletterForm->isSubmitted() === false) {
            $newsletterForm->setData($this->getFormData($customerTransfer));
        }

        if ($newsletterForm->isValid()) {
            $subscribe = (bool)$newsletterForm->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)->getData();
            $this->processSubscriptionForm($subscribe, $customerTransfer);

            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_NEWSLETTER);
        }

        return $this->view([
            'customer' => $customerTransfer,
            'form' => $newsletterForm->createView(),
        ]);
    }

    /**
     * @param bool $subscribe
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function processSubscriptionForm($subscribe, CustomerTransfer $customerTransfer)
    {
        if ($subscribe === true) {
            $this->processSubscription($customerTransfer);

            return;
        }

        $this->processUnsubscription($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function processSubscription(CustomerTransfer $customerTransfer)
    {
        $request = $this->createNewsletterSubscriptionRequest($customerTransfer);
        $subscriptionResponse = $this->getFactory()
            ->getNewsletterClient()
            ->subscribeWithDoubleOptIn($request);

        $subscriptionResult = current($subscriptionResponse->getSubscriptionResults());
        if ($subscriptionResult->getIsSuccess()) {
            $this->addSuccessMessage(self::MESSAGE_SUBSCRIPTION_SUCCESS);

            return;
        }

        $this->addErrorMessage($subscriptionResult->getErrorMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function processUnsubscription(CustomerTransfer $customerTransfer)
    {
        $request = $this->createNewsletterSubscriptionRequest($customerTransfer);

        $this->getFactory()
            ->getNewsletterClient()
            ->unsubscribe($request);

        $this->addSuccessMessage(self::MESSAGE_UNSUBSCRIPTION_SUCCESS);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getFormData(CustomerTransfer $customerTransfer)
    {
        $request = $this->createNewsletterSubscriptionRequest($customerTransfer);

        $subscriptionResultTransfer = $this->getFactory()
            ->getNewsletterClient()
            ->checkSubscription($request);

        return [
            NewsletterSubscriptionForm::FIELD_SUBSCRIBE => $subscriptionResultTransfer->getSubscriptionResults()[0]->getIsSuccess(),
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
            ->setName(NewsletterConstants::DEFAULT_NEWSLETTER));

        return $subscriptionRequest;
    }
}
