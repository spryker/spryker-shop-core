<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductConcreteAddController extends AbstractController
{
    protected const REFERER_PARAM = 'referer';
    protected const PRODUCT_QUICK_ADD_FORM_ANCHOR = '#product-quick-add-form-wrapper';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $form = $this->getFactory()
            ->getProductQuickAddForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorFlashMessagesFromForm($form);
            $referer = $this->getReferWithAnchor($request->headers);

            return $this->redirectResponseExternal($referer);
        }
        $redirectRouteName = $form->getData()[ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME];

        return $this->redirectResponseInternal($redirectRouteName, $request->request->all());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\HeaderBag $headers
     *
     * @return string
     */
    protected function getReferWithAnchor(HeaderBag $headers): string
    {
        return $headers->get(static::REFERER_PARAM) . static::PRODUCT_QUICK_ADD_FORM_ANCHOR;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrorFlashMessagesFromForm(FormInterface $form): void
    {
        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
