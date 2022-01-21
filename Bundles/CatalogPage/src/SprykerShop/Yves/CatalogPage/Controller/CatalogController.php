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
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 * @method \Spryker\Client\Catalog\CatalogClientInterface getClient()
 */
class CatalogController extends AbstractController
{
    use PermissionAwareTrait;

    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @var string
     */
    public const MESSAGE_PAGE_NOT_FOUND = 'catalog.page.not_found';

    /**
     * @var string
     */
    public const URL_PARAM_VIEW_MODE = 'mode';

    /**
     * @var string
     */
    public const URL_PARAM_REFERER_URL = 'referer-url';

    /**
     * @var string
     */
    protected const URL_PARAM_FILTER_BY_PRICE = 'price';

    /**
     * @var string
     */
    protected const URL_PARAM_SORTING = 'sort';

    /**
     * @var array
     */
    protected const PRICE_SORTING_DIRECTIONS = ['price_desc', 'price_asc'];

    /**
     * @var string
     */
    protected const MESSAGE_PAGE_CANNOT_BE_OPENED = 'catalog.page.cannot_be_opened';

    /**
     * @uses \SprykerShop\Yves\CatalogPage\Plugin\Router\CatalogPageRouteProviderPlugin::ROUTE_NAME_SEARCH
     *
     * @var string
     */
    protected const ROUTE_SEARCH_PAGE = 'search';

    /**
     * @param array<string, mixed> $categoryNode
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
            $this->getCategoryNodeTemplate($idCategoryNode),
        );
    }

    /**
     * @param array<string, mixed> $categoryNode
     * @param int $idCategoryNode
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(array $categoryNode, int $idCategoryNode, Request $request): array
    {
        $searchString = (string)$request->query->get('q', '');
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

        $searchResults = $this->reduceRestrictedSortingOptions($searchResults);
        $searchResults = $this->updateFacetFiltersByCategory($searchResults, $idCategory);
        $searchResults = $this->filterFacetsInSearchResults($searchResults);

        $metaTitle = $categoryNode['meta_title'] ?? '';
        $metaDescription = $categoryNode['meta_description'] ?? '';
        $metaKeywords = $categoryNode['meta_keywords'] ?? '';

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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fulltextSearchAction(Request $request)
    {
        try {
            $viewData = $this->executeFulltextSearchAction($request);
        } catch (Throwable $e) {
            if (preg_match('/Search failed with the following reason: Numeric value \(\d+\) out of range of int.*/', $e->getMessage())) {
                throw new NotFoundHttpException();
            }
            $this->addErrorMessage(static::MESSAGE_PAGE_CANNOT_BE_OPENED);

            return $this->redirectResponseInternal(static::ROUTE_SEARCH_PAGE);
        }

        return $this->view(
            $viewData,
            $this->getFactory()->getCatalogPageWidgetPlugins(),
            '@CatalogPage/views/search/search.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function executeFulltextSearchAction(Request $request): array
    {
        $searchString = (string)$request->query->get('q', '');

        $searchResults = $this
            ->getFactory()
            ->getCatalogClient()
            ->catalogSearch($searchString, $this->getAllowedRequestParameters($request));

        $searchResults = $this->reduceRestrictedSortingOptions($searchResults);
        $searchResults = $this->filterFacetsInSearchResults($searchResults);

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
        $viewMode = (string)$request->query->get(static::URL_PARAM_VIEW_MODE) ?: null;
        $refererUrl = (string)$request->query->get(static::URL_PARAM_REFERER_URL);

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

        $storeName = $this->getFactory()
            ->getStoreClient()
            ->getCurrentStore()
            ->getName();

        $categoryNodeStorageTransfer = $this->getFactory()
            ->getCategoryStorageClient()
            ->getCategoryNodeById($idCategoryNode, $localeName, $storeName);

        return $categoryNodeStorageTransfer->getTemplatePath();
    }

    /**
     * @param array<string, mixed> $searchResults
     * @param int $idCategory
     *
     * @return array<string, mixed>
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
                $categoryFilters,
            );

        return $searchResults;
    }

    /**
     * @param array<string, mixed> $searchResults
     *
     * @return array<string, mixed>
     */
    protected function filterFacetsInSearchResults(array $searchResults): array
    {
        if (!isset($searchResults[FacetResultFormatterPlugin::NAME])) {
            return $searchResults;
        }

        $searchResults['filteredFacets'] = $this->getFactory()
            ->createFacetFilter()
            ->getFilteredFacets($searchResults[FacetResultFormatterPlugin::NAME]);

        return $searchResults;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function getAllowedRequestParameters(Request $request): array
    {
        $parameters = $request->query->all();
        $parameters = $this->reduceRestrictedParameters($parameters);

        return $parameters;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<string, mixed>
     */
    protected function reduceRestrictedParameters(array $parameters): array
    {
        $shopContextParameters = $this->getFactory()
            ->getShopContext()
            ->modifiedToArray();
        $parameters = array_merge($parameters, $shopContextParameters);

        if ($this->can('SeePricePermissionPlugin')) {
            return $parameters;
        }

        if ($this->isPriceFilteringParametersExist($parameters)) {
            unset($parameters[static::URL_PARAM_FILTER_BY_PRICE]);
        }

        if ($this->isPriceSortingParametersExist($parameters)) {
            unset($parameters[static::URL_PARAM_SORTING]);
        }

        return $parameters;
    }

    /**
     * @param array<string, mixed> $searchResults
     *
     * @return array<string, mixed>
     */
    protected function reduceRestrictedSortingOptions(array $searchResults): array
    {
        if (!$this->can('SeePricePermissionPlugin') && isset($searchResults[static::URL_PARAM_SORTING])) {
            $sortParamNames = $searchResults[static::URL_PARAM_SORTING]->getSortParamNames();
            $grantedSortParamNames = array_diff($sortParamNames, static::PRICE_SORTING_DIRECTIONS);
            $searchResults[static::URL_PARAM_SORTING]->setSortParamNames($grantedSortParamNames);
        }

        return $searchResults;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return bool
     */
    protected function isPriceFilteringParametersExist(array $parameters): bool
    {
        return isset($parameters[static::URL_PARAM_FILTER_BY_PRICE]);
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return bool
     */
    protected function isPriceSortingParametersExist(array $parameters): bool
    {
        return isset($parameters[static::URL_PARAM_SORTING]) && in_array($parameters[static::URL_PARAM_SORTING], static::PRICE_SORTING_DIRECTIONS);
    }
}
