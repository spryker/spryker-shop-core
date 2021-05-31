<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class ProductRelationController extends AbstractController
{
    protected const KEY_CODE = 'code';
    protected const KEY_HTML = 'html';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUpsellingProductsWidgetAjaxAction(Request $request): JsonResponse
    {
        $response = $this->executeGetUpsellingProductsWidgetAjaxAction();

        return $this->jsonResponse(
            $response
        );
    }

    /**
     * @return string[]
     */
    protected function executeGetUpsellingProductsWidgetAjaxAction(): array
    {
        $viewData = [
           'cart' => $this->getFactory()->getCartClient()->getQuote(),
        ];

        $upsellingWidgetHtml = $this->renderView(
            '@ProductRelationWidget/views/ajax-upselling-widget/ajax-upselling-widget.twig',
            $viewData
        )->getContent();

        return [
            static::KEY_CODE => Response::HTTP_OK,
            static::KEY_HTML => $upsellingWidgetHtml,
        ];
    }
}
