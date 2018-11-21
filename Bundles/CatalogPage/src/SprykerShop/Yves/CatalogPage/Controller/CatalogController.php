<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Controller;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Shared\CatalogPage\Plugin\SeePricePermissionPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 * @method \Spryker\Client\Catalog\CatalogClientInterface getClient()
 */
class CatalogController extends AbstractController
{
    use PermissionAwareTrait;

    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    public const URL_PARAM_VIEW_MODE = 'mode';
    public const URL_PARAM_REFERER_URL = 'referer-url';

    protected const URL_PARAM_FILTER_BY_PRICE = 'price';

    /**
     * @param array $categoryNode
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(array $categoryNode, Request $request)
    {
        $idCategoryNode = $categoryNode['node_id'];

        $viewData = $this->executeIndexAction($categoryNode, $idCategoryNode, $request);

        return $this->view(
            $viewData,
            $this->getFactory()->getCatalogPageWidgetPlugins(),
            $this->getCategoryNodeTemplate($idCategoryNode)
        );
    }

    /**
     * @param array $categoryNode
     * @param int $idCategoryNode
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(array $categoryNode, int $idCategoryNode, Request $request): array
    {
        $searchString = $request->query->get('q', '');
        $idCategory = $categoryNode['id_category'];
        $isEmptyCategoryFilterValueVisible = $this->getFactory()
            ->getModuleConfig()
            ->isEmptyCategoryFilterValueVisible();

        $parameters = $this->getAllowedRequestParameters($request);
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
            'idCategory' => $idCategory,
            'category' => $categoryNode,
            'isEmptyCategoryFilterValueVisible' => $isEmptyCategoryFilterValueVisible,
            'pageTitle' => ($metaTitle ?: $categoryNode['name']),
            'pageDescription' => $metaDescription,
            'pageKeywords' => $metaKeywords,
            'searchString' => $searchString,
            'viewMode' => $this->getFactory()
                ->getCatalogClient()
                ->getCatalogViewMode($request),
        ];

        return array_merge($searchResults, $metaAttributes);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function fulltextSearchAction(Request $request)
    {
        $viewData = $this->executeFulltextSearchAction($request);

        return $this->view(
            $viewData,
            $this->getFactory()->getCatalogPageWidgetPlugins(),
            '@CatalogPage/views/search/search.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeFulltextSearchAction(Request $request): array
    {
        $searchString = $request->query->get('q');

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearch($searchString, $this->getAllowedRequestParameters($request));

        $isEmptyCategoryFilterValueVisible = $this->getFactory()
            ->getModuleConfig()
            ->isEmptyCategoryFilterValueVisible();

        $searchResults['searchString'] = $searchString;
        $searchResults['idCategory'] = null;
        $searchResults['isEmptyCategoryFilterValueVisible'] = $isEmptyCategoryFilterValueVisible;
        $searchResults['viewMode'] = $this->getFactory()
            ->getCatalogClient()
            ->getCatalogViewMode($request);

        return $searchResults;
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

        $categoryFilters = [];
        $categoryFiltersData = $productCategoryFilters->getFilterData();
        foreach ($categoryFiltersData['filters'] as $filterData) {
            $categoryFilters[$filterData['key']] = $filterData['isActive'];
        }

        $searchResults[FacetResultFormatterPlugin::NAME] = $this->getFactory()->getProductCategoryFilterClient()
            ->updateFacetsByCategory(
                $searchResults[FacetResultFormatterPlugin::NAME],
                $categoryFilters
            );

        return $searchResults;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getAllowedRequestParameters(Request $request): array
    {
        $parameters = $request->query->all();
        $parameters = $this->reduceRestrictedParameters($parameters);

        return $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function reduceRestrictedParameters(array $parameters): array
    {
        if (isset($parameters[static::URL_PARAM_FILTER_BY_PRICE]) && !$this->canFilterByPrices()) {
            unset($parameters[static::URL_PARAM_FILTER_BY_PRICE]);
        }

        return $parameters;
    }

    /**
     * @return bool
     */
    protected function canFilterByPrices(): bool
    {
        return $this->can(SeePricePermissionPlugin::KEY);
    }
}
