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
     * @param string $shareOptionGroup
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(int $idQuote, string $shareOptionGroup, Request $request): View
    {
        $response = $this->executeIndexAction($idQuote, $shareOptionGroup, $request);

        return $this->view($response, [], '@PersistentCartShareWidget/views/share-cart-response/share-cart-response.twig');
    }

    /**
     * @param int $idQuote
     * @param string $shareOptionGroup
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(int $idQuote, string $shareOptionGroup, Request $request): array
    {
        $shareOptions = $this->getFactory()
            ->getPersistentCartShareClient()
            ->getCartShareOptions();

        $cartShareLinks = $this->getFactory()
            ->createPersistentCartShareLinkGenerator()
            ->generateCartShareLinks($shareOptions, $idQuote, $shareOptionGroup);

        $cartShareLinkLabels = $this->getFactory()
            ->createPersistentCartShareLinkGenerator()
            ->generateCartShareLinkLabels($shareOptions, $idQuote, $shareOptionGroup);

        return [
            'idQuote' => $idQuote,
            'shareOptionGroup' => $shareOptionGroup,
            'cartShareLinks' => $cartShareLinks,
            'cartShareLinkLabels' => $cartShareLinkLabels,
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
