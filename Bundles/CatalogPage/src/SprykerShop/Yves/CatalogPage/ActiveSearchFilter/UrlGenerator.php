<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\ActiveSearchFilter;

use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use InvalidArgumentException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerShop\Yves\CatalogPage\CatalogPageConfig;
use SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface;
use Symfony\Component\HttpFoundation\Request;

class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \SprykerShop\Yves\CatalogPage\CatalogPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\CatalogPage\Dependency\Client\CatalogPageToSearchClientInterface $searchClient
     * @param \SprykerShop\Yves\CatalogPage\CatalogPageConfig $config
     */
    public function __construct(CatalogPageToSearchClientInterface $searchClient, CatalogPageConfig $config)
    {
        $this->searchClient = $searchClient;
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer $searchResultTransfer
     * @param string $filterValue
     *
     * @return string
     */
    public function generateUrlWithoutActiveSearchFilter(Request $request, TransferInterface $searchResultTransfer, $filterValue)
    {
        $params = $request->query->all();
        $params = $this->removePaginationFromParams($params);
        $params = $this->processSearchResultTransfer($params, $searchResultTransfer, $filterValue);

        return Url::generate($request->getPathInfo(), $params)->build();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[] $facetFilters
     *
     * @return string
     */
    public function generateUrlWithoutAllActiveSearchFilters(Request $request, array $facetFilters)
    {
        $params = $request->query->all();
        $params = $this->removePaginationFromParams($params);

        foreach ($facetFilters as $searchResultTransfer) {
            if (!isset($params[$searchResultTransfer->getConfig()->getParameterName()])) {
                continue;
            }

            $params = $this->processSearchResultTransfer($params, $searchResultTransfer);
        }

        return Url::generate($request->getPathInfo(), $params)->build();
    }

    /**
     * @param array $params
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer $searchResultTransfer
     * @param string|null $filterValue
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function processSearchResultTransfer(array $params, TransferInterface $searchResultTransfer, $filterValue = null)
    {
        switch (get_class($searchResultTransfer)) {
            case FacetSearchResultTransfer::class:
                $params = $this->processFacetSearchResultTransfer($params, $searchResultTransfer, $filterValue);
                break;

            case RangeSearchResultTransfer::class:
                $params = $this->processRangeSearchResultTransfer($params, $searchResultTransfer);
                break;

            default:
                throw new InvalidArgumentException(sprintf(
                    'Invalid search result transfer "%s',
                    get_class($searchResultTransfer)
                ));
        }

        return $params;
    }

    /**
     * @param array $params
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $searchResultTransfer
     * @param string|null $filterValue
     *
     * @return array
     */
    protected function processFacetSearchResultTransfer(array $params, FacetSearchResultTransfer $searchResultTransfer, $filterValue = null)
    {
        $parameterName = $searchResultTransfer->getConfig()->getParameterName();
        $param = $params[$parameterName];
        if (is_array($param) && $filterValue !== null) {
            $index = array_search($filterValue, $param);
            unset($params[$parameterName][$index]);

            return $params;
        }

        unset($params[$parameterName]);

        return $params;
    }

    /**
     * @param array $params
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $searchResultTransfer
     *
     * @return array
     */
    protected function processRangeSearchResultTransfer(array $params, RangeSearchResultTransfer $searchResultTransfer)
    {
        unset($params[$searchResultTransfer->getConfig()->getParameterName()]);

        return $params;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function removePaginationFromParams(array $params)
    {
        $paginationParameterName = $this->config->getParameterNamePage();

        if (isset($params[$paginationParameterName])) {
            unset($params[$paginationParameterName]);
        }

        return $params;
    }
}
