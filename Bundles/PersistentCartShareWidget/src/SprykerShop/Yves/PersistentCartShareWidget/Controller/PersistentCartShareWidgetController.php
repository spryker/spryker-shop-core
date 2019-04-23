<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShareWidgetFactory getFactory()
 */
class PersistentCartShareWidgetController extends AbstractController
{

    /**
     * @param int $idQuote
     * @param string $permissionOption
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(int $idQuote, string $permissionOption, Request $request): View
    {
        $response = $this->executeIndexAction($idQuote, $permissionOption, $request);

        return $this->view($response, [], '@PersistentCartShareWidget/views/share-cart-response/share-cart-response.twig');
    }

    /**
     * @param int $idQuote
     * @param string $permissionOption
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(int $idQuote, string $permissionOption, Request $request)
    {
        if ($permissionOption === 'internal') {
            $links = [
                'READ_ONLY' => 'http://www.de.suite-nonsplit.local/read-only',
                'FULL_ACCESS' => 'http://www.de.suite-nonsplit.local/full-access',
            ];
            $labels = [
                'READ_ONLY' => 'persistent_cart_share.share_options.READ_ONLY',
                'FULL_ACCESS' => 'persistent_cart_share.share_options.FULL_ACCESS',
            ];
        } else {
            $links = [
                'PREVIEW' => 'http://www.de.suite-nonsplit.local/preview',
            ];
            $labels = [
                'PREVIEW' => '',
            ];
        }

        return [
            'idQuote' => $idQuote,
            'permissionOption' => $permissionOption,
            'links' => $links,
            'labels' => $labels,
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
