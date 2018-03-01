<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Controller;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 * @method \Spryker\Client\Catalog\CatalogClientInterface getClient()
 */
class CatalogController extends AbstractController
{
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    const URL_PARAM_VIEW_MODE = 'mode';
    const URL_PARAM_REFERER_URL = 'referer-url';

    /**
     * @param array $categoryNode
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(array $categoryNode, Request $request)
    {
        $searchString = $request->query->get('q', '');
        $idCategoryNode = $categoryNode['node_id'];
        $idCategory = $categoryNode['id_category'];

        $parameters = $request->query->all();
        $parameters[PageIndexMap::CATEGORY] = $idCategoryNode;

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearch($searchString, $parameters);

        $searchResults = $this->updateFacetFiltersByCategory($searchResults, $idCategory);
        $metaTitle = isset($categoryNode['meta_title']) ? $categoryNode['meta_title'] : '';
        $metaDescription = isset($categoryNode['meta_description']) ? $categoryNode['meta_description'] : '';
        $metaKeywords = isset($categoryNode['meta_keywords']) ? $categoryNode['meta_keywords'] : '';

        $metaAttributes = [
            'idCategory' => $idCategoryNode,
            'category' => $categoryNode,
            'pageTitle' => ($metaTitle ?: $categoryNode['name']),
            'pageDescription' => $metaDescription,
            'pageKeywords' => $metaKeywords,
            'searchString' => $searchString,
            'viewMode' => $this->getFactory()
                ->getCatalogClient()
                ->getCatalogViewMode($request),
        ];

        $searchResults = array_merge($searchResults, $metaAttributes);
        $template = $this->getCategoryNodeTemplate($idCategoryNode);

        return $this->view(
            $searchResults,
            $this->getFactory()->getCatalogPageWidgetPlugins(),
            $template
        );
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
        $searchResults['viewMode'] = $this->getFactory()
            ->getCatalogClient()
            ->getCatalogViewMode($request);

        return $this->view(
            $searchResults,
            $this->getFactory()->getCatalogPageWidgetPlugins(),
            '@CatalogPage/views/search/search.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changeViewModeAction(Request $request)
    {
        $viewMode = $request->query->get(static::URL_PARAM_VIEW_MODE);
        $refererUrl = $request->query->get(static::URL_PARAM_REFERER_URL);

        $response = $this->redirectResponseExternal($refererUrl);

        return $this->getFactory()
            ->getCatalogClient()
            ->setCatalogViewMode($viewMode, $response);
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

        $categoryNodeStorageTransfer = $this->getFactory()
            ->getCategoryStorageClient()
            ->getCategoryNodeById($idCategoryNode, $localeName);

        return $categoryNodeStorageTransfer->getTemplatePath();
    }

    /**
     * @param array $searchResults
     * @param int $idCategory
     *
     * @return array
     */
    protected function updateFacetFiltersByCategory(array $searchResults, $idCategory)
    {
        if (!isset($searchResults[FacetResultFormatterPlugin::NAME])) {
            return $searchResults;
        }

        $productCategoryFilters = $this->getFactory()->getProductCategoryFilterStorageClient()->getProductCategoryFilterByIdCategory($idCategory);
        if (!$productCategoryFilters) {
            return $searchResults;
        }

        $searchResults[FacetResultFormatterPlugin::NAME] = $this->getFactory()->getProductCategoryFilterClient()
            ->updateCategoryFacets(
                $searchResults[FacetResultFormatterPlugin::NAME],
                $idCategory,
                $this->getLocale()
            );

        return $searchResults;
    }
}
