<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage\Controller;

use Generated\Shared\Search\PageIndexMap;
use Pyz\Yves\Application\Controller\AbstractController;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 * @method \Spryker\Client\Catalog\CatalogClientInterface getClient()
 */
class CatalogController extends AbstractController
{

    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @param array $categoryNode
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(array $categoryNode, Request $request)
    {
        $searchString = $request->query->get('q', '');

        $parameters = $request->query->all();
        $parameters[PageIndexMap::CATEGORY] = $categoryNode['node_id'];

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearch($searchString, $parameters);

        $pageTitle = ($categoryNode['meta_title']) ?: $categoryNode['name'];
        $metaAttributes = [
            'idCategory' => $parameters['category'],
            'category' => $categoryNode,
            'page_title' => $pageTitle,
            'page_description' => $categoryNode['meta_description'],
            'page_keywords' => $categoryNode['meta_keywords'],
            'searchString' => $searchString,
        ];

        $searchResults = array_merge($searchResults, $metaAttributes);
        $template = $this->getCategoryNodeTemplate($categoryNode['node_id']);

        $view = new \Spryker\Yves\Kernel\View\View($searchResults, $template);
        $view->addWidgets($this->getFactory()->getCatalogWidgetBuilderPlugins(), $request);

        return $view;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function fulltextSearchAction(Request $request)
    {
        $searchString = $request->query->get('q');

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearch($searchString, $request->query->all());

        $searchResults['searchString'] = $searchString;
        $searchResults['idCategory'] = null;

        $view = new View($searchResults);
        $view->addWidgets($this->getFactory()->getCatalogWidgetBuilderPlugins(), $request);

        return $view;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return string|null
     */
    protected function getCategoryNodeTemplate($idCategoryNode)
    {
        $localeName = $this->getFactory()
            ->getLocaleClient()
            ->getCurrentLocale();

        return $this->getFactory()
            ->getCategoryClient()
            ->getTemplatePathByNodeId($idCategoryNode, $localeName);
    }

}
