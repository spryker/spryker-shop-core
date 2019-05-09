<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Controller;

use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CodeController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartCodeForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = (string)$form->get(CartCodeForm::FIELD_CODE)->getData();

            $this->getFactory()
                ->getCartCodeClient()
                ->addCode($code);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART); // TODO: redirect to the same page where the request came from
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $code = $request->query->get('code');
        if (!empty($code)) {
            $this->getFactory()
                ->getCartCodeClient()
                ->removeCode($code);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART); // TODO: redirect to the same page where the request came from
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction()
    {
        $this->getFactory()
            ->getCartCodeClient()
            ->clearCodes();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART); // TODO: redirect to the same page where the request came from
    }
}
