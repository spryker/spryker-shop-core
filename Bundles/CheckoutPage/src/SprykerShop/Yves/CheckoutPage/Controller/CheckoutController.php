<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CheckoutPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 */
class CheckoutController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $response = $this->createStepProcess()->process($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function customerAction(Request $request)
    {
        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createCustomerFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, $this->getFactory()->getCustomerPageWidgetPlugins());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function addressAction(Request $request)
    {
        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createAddressFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, $this->getFactory()->getCustomerPageWidgetPlugins());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function shipmentAction(Request $request)
    {
        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createShipmentFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, $this->getFactory()->getCustomerPageWidgetPlugins());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function paymentAction(Request $request)
    {
        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createPaymentFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, $this->getFactory()->getCustomerPageWidgetPlugins());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function summaryAction(Request $request)
    {
        $viewData = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createSummaryFormCollection()
        );

        if (!is_array($viewData)) {
            return $viewData;
        }

        return $this->view($viewData, $this->getFactory()->getSummaryPageWidgetPlugins());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderAction(Request $request)
    {
        return $this->createStepProcess()->process($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function successAction(Request $request)
    {
        $response = $this->createStepProcess()->process($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, $this->getFactory()->getCustomerPageWidgetPlugins());
    }

    /**
     * @return array
     */
    public function errorAction()
    {
        return [];
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    protected function createStepProcess()
    {
        return $this->getFactory()->createCheckoutProcess();
    }
}
