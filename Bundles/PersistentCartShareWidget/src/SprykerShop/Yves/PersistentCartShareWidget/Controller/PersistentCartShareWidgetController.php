<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PersistentCartShareWidgetController extends AbstractController
{

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

//        $customerTransfer = $this->getCustomer();
//
//        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
//            throw new NotFoundHttpException("Only company users are allowed to access this page");
//        }
    }

    /**
     * @param int $idQuote
     * @param string $permissionOption
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(int $idQuote, string $permissionOption, Request $request)
    {
        $response = $this->executeIndexAction($idQuote, $permissionOption);

        return $this->view($response, [], '@PersistentCartShareWidget/views/share-cart-by-link-widget/share-cart-by-link-widget.twig');
    }

    /**
     * @param int $idQuote
     * @param string $permissionOption
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(int $idQuote, string $permissionOption)
    {
        return [
            'idQuote' => $idQuote,
            'permissionOption' => $permissionOption,
            'links' => [
                'READ_ONLY' => 'http://www.de.suite-nonsplit.local/read-only',
                'FULL_ACCESS' => 'http://www.de.suite-nonsplit.local/full-access',
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }
}
