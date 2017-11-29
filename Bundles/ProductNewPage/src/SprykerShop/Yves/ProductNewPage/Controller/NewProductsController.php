<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductNewPage\Controller;

use SprykerShop\Yves\ProductNewPage\Plugin\Provider\ProductNewPageControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductNewPage\ProductNewPageFactory getFactory()
 */
class NewProductsController extends AbstractController
{

    /**
     * @param string $categoryPath
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction($categoryPath, Request $request)
    {
        $parameters = $request->query->all();

        $categoryNode = [];
        if ($categoryPath) {
            $categoryNode = $this->getFactory()
                ->getCategoryReaderPlugin()
                ->findCategoryNodeByPath($categoryPath);

            $parameters['category'] = $categoryNode['node_id'];
        }

        $searchResults = $this
            ->getFactory()
            ->getProductNewClient()
            ->findNewProducts($parameters);

        $searchResults['category'] = $categoryNode;
        $searchResults['filterPath'] = ProductNewPageControllerProvider::ROUTE_NEW_PRODUCTS;

        return $this->view($searchResults, $this->getFactory()->getNewProductPageWidgetPlugins());
    }

}
