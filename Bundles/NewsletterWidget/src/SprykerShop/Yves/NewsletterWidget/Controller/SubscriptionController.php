<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Yves\Application\Controller\AbstractController;
use SprykerShop\Yves\NewsletterWidget\Form\NewsletterSubscriptionForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 * @method \SprykerShop\Client\NewsletterWidget\NewsletterWidgetClientInterface getClient()
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

        if ($subscriptionForm->isValid()) {
            $customerTransfer = (new CustomerTransfer())
                ->setEmail($subscriptionForm->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)->getData());

            $subscriptionResponse = $this
                ->getClient()
                ->subscribeForEditorialNewsletter($customerTransfer);

            if ($subscriptionResponse->getIsSuccess()) {
                $subscriptionForm = $this
                    ->getFactory()
                    ->getNewsletterSubscriptionForm();
                $success = 'newsletter.subscription.success';
            }

            if (!$subscriptionResponse->getIsSuccess()) {
                $error = $subscriptionResponse->getErrorMessage();
            }
        }

        return $this->view([
            'newsletterSubscriptionForm' => $subscriptionForm->createView(),
            'error' => $error,
            'success' => $success,
        ]);
    }
}
