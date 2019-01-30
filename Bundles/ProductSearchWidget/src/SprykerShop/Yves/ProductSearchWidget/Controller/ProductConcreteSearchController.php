<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductConcreteSearchController extends AbstractController
{
    /**
     * @uses ProductConcreteCatalogSearchResultFormatterPlugin::NAME
     */
    protected const PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_NAME = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    protected const PARAM_SEARCH_STRING = 'searchString';
    protected const PARAM_LIMIT = 'limit';
    protected const REFERER_PARAM = 'referer';
    protected const PRODUCT_QUICK_ADD_FORM_ANCHOR = '#product-quick-add-form-wrapper';
    protected const MESSAGE_QUICK_ADD_TO_CART_INCORRECT_INPUT_DATA = 'cart.quick_add_to_cart.incorrect_input_data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $productConcreteCriteriaFilterTransfer = $this->createProductConcreteCriteriaFilterTransfer($request);
        $products = $this->searchProducts($productConcreteCriteriaFilterTransfer);

        return $this->view(
            $products,
            [],
            '@ProductSearchWidget/views/product-search-results/product-search-results.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request): RedirectResponse
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
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array
     */
    protected function searchProducts(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array
    {
        $formattedProducts = $this->getFactory()
            ->getCatalogClient()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        return $formattedProducts[static::PRODUCT_CONCRETE_CATALOG_SEARCH_RESULT_FORMATTER_PLUGIN_NAME] ?? [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(Request $request): ProductConcreteCriteriaFilterTransfer
    {
        $productConcreteCriteriaFilterTransfer = new ProductConcreteCriteriaFilterTransfer();

        $productConcreteCriteriaFilterTransfer->setSearchString($request->get(static::PARAM_SEARCH_STRING));
        $productConcreteCriteriaFilterTransfer->setLimit($request->get(static::PARAM_LIMIT));

        return $productConcreteCriteriaFilterTransfer;
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
