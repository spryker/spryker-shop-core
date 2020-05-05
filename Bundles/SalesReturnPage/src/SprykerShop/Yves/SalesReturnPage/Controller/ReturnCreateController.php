<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Controller;

use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageFactory getFactory()
 */
class ReturnCreateController extends AbstractReturnController
{
    protected const GLOSSARY_KEY_RETURN_CREATED = 'return_page.return.created';

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Plugin\Router\SalesReturnPageRouteProviderPlugin::ROUTE_RETURN_VIEW
     */
    protected const ROUTE_RETURN_VIEW = 'return/view';

    protected const PARAM_RETURN_REFERENCE = 'returnReference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, string $orderReference)
    {
        $response = $this->executecreateAction($request, $orderReference);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            [],
            '@SalesReturnPage/views/return-create/return-create.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request, string $orderReference)
    {
        $orderTransfer = $this->getOrderByOrderReference($orderReference);

        $returnCreateForm = $this->getFactory()
            ->getCreateReturnForm($orderTransfer)
            ->handleRequest($request);

        if ($returnCreateForm->isSubmitted() && $returnCreateForm->isValid()) {
            return $this->processReturnCreateForm($returnCreateForm, $orderTransfer);
        }

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param string $orderReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderByOrderReference(string $orderReference): OrderTransfer
    {
        $orderListRequestTransfer = (new OrderListRequestTransfer())
            ->setCustomerReference($this->getFactory()->getCustomerClient()->getCustomer()->getCustomerReference())
            ->setOrderReferences([$orderReference]);

        $orderTransfersCollection = $this->getFactory()
            ->getSalesClient()
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders();

        if (!$orderTransfersCollection->count()) {
            throw new NotFoundHttpException();
        }

        return $orderTransfersCollection->offsetGet(0);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processReturnCreateForm(FormInterface $returnCreateForm, OrderTransfer $orderTransfer)
    {
        $returnResponseTransfer = $this->getFactory()
            ->createReturnHandler()
            ->createReturn($returnCreateForm->getData());

        if ($returnResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_RETURN_CREATED);

            return $this->redirectResponseInternal(static::ROUTE_RETURN_VIEW, [
                static::PARAM_RETURN_REFERENCE => $returnResponseTransfer->getReturn()->getReturnReference(),
            ]);
        }

        $this->handleResponseErrors($returnResponseTransfer);

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }
}
