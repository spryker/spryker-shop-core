<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\ProductNewPage\Plugin\Provider\ProductNewPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ProductNewPage\ProductNewPageFactory getFactory()
 */
class NewProductsController extends AbstractController
{
    /**
     * @param string $categoryPath
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction($categoryPath, Request $request)
    {
        $viewData = $this->executeIndexAction($categoryPath, $request);

        return $this->view(
            $viewData,
            $this->getFactory()->getProductNewPageWidgetPlugins(),
            '@ProductNewPage/views/new-product/new-product.twig'
        );
    }

    /**
     * @param string $categoryPath
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeIndexAction($categoryPath, Request $request): array
    {
        $parameters = $request->query->all();

        $categoryNode = [];
        if ($categoryPath) {
            $categoryNode = $this->findCategoryNode($categoryPath);

            if (!$categoryNode) {
                throw new NotFoundHttpException(sprintf(
                    'Category not found by path %s',
                    $categoryPath
                ));
            }

            $parameters['category'] = $categoryNode['node_id'];
        }

        $searchResults = $this
            ->getFactory()
            ->getProductNewClient()
            ->findNewProducts($parameters);

        $searchResults['category'] = $categoryNode;
        $searchResults['filterPath'] = ProductNewPageControllerProvider::ROUTE_NEW_PRODUCTS;
        $searchResults['viewMode'] = $this->getFactory()
            ->getCatalogClient()
            ->getCatalogViewMode($request);

        return $searchResults;
    }

    /**
     * @param string $categoryPath
     *
     * @return array
     */
    protected function findCategoryNode($categoryPath): ?array
    {
        $categoryPathPrefix = '/' . $this->getFactory()->getStore()->getCurrentLanguage();
        $categoryPath = $categoryPathPrefix . '/' . ltrim($categoryPath, '/');

        $categoryNode = $this->getFactory()
            ->getUrlStorageClient()
            ->matchUrl($categoryPath, $this->getLocale());

        return $categoryNode ? $categoryNode['data'] : [];
    }
}
