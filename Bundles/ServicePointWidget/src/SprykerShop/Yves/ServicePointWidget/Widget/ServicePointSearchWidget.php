<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Widget;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig getConfig()
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 */
class ServicePointSearchWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'ServicePointSearchWidget';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_RESULT_LIMIT = 'searchResultLimit';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_RESULTS = 'searchResults';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_ROUTE = 'searchRoute';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     *
     * @uses \SprykerShop\Yves\ServicePointWidget\Plugin\Router\ServicePointWidgetRouteProviderPlugin::ROUTE_NAME_SEARCH
     */
    protected const ROUTE_NAME_SEARCH = 'service-point-widget/search';

    /**
     * @param string|null $serviceTypeKey
     * @param int|null $searchResultLimit
     * @param bool $isInitialRenderEnabled
     */
    public function __construct(
        ?string $serviceTypeKey = null,
        ?int $searchResultLimit = null,
        bool $isInitialRenderEnabled = true
    ) {
        if (!$searchResultLimit) {
            $searchResultLimit = $this->getConfig()->getSearchResultLimit();
        }

        $this->addSearchResultLimitParameter($searchResultLimit);
        $this->addSearchResultsParameter($isInitialRenderEnabled, $searchResultLimit, $serviceTypeKey);
        $this->addSearchRouteParameter();
        $this->addServiceTypeKeyParameter($serviceTypeKey);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ServicePointWidget/views/service-point-search/service-point-search.twig';
    }

    /**
     * @param int $searchResultLimit
     *
     * @return void
     */
    protected function addSearchResultLimitParameter(int $searchResultLimit): void
    {
        $this->addParameter(static::PARAMETER_SEARCH_RESULT_LIMIT, $searchResultLimit);
    }

    /**
     * @param bool $isInitialRenderEnabled
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     *
     * @return void
     */
    protected function addSearchResultsParameter(
        bool $isInitialRenderEnabled,
        int $searchResultLimit,
        ?string $serviceTypeKey = null
    ): void {
        $this->addParameter(
            static::PARAMETER_SEARCH_RESULTS,
            $this->getSearchResults($isInitialRenderEnabled, $searchResultLimit, $serviceTypeKey),
        );
    }

    /**
     * @return void
     */
    protected function addSearchRouteParameter(): void
    {
        $this->addParameter(static::PARAMETER_SEARCH_ROUTE, static::ROUTE_NAME_SEARCH);
    }

    /**
     * @param string|null $serviceTypeKey
     *
     * @return void
     */
    protected function addServiceTypeKeyParameter(?string $serviceTypeKey = null): void
    {
        $this->addParameter(static::PARAMETER_SERVICE_TYPE_KEY, $serviceTypeKey);
    }

    /**
     * @param bool $isInitialRenderEnabled
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     *
     * @return string
     */
    protected function getSearchResults(
        bool $isInitialRenderEnabled,
        int $searchResultLimit,
        ?string $serviceTypeKey = null
    ): string {
        if (!$isInitialRenderEnabled) {
            return '';
        }

        $servicePointSearchRequestTransfer = $this->createServicePointSearchRequestTransfer(
            $searchResultLimit,
            $serviceTypeKey,
        );

        return $this->getFactory()
            ->createServicePointReader()
            ->searchServicePoints($servicePointSearchRequestTransfer);
    }

    /**
     * @param int $searchResultLimit
     * @param string|null $serviceTypeKey
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchRequestTransfer
     */
    protected function createServicePointSearchRequestTransfer(
        int $searchResultLimit,
        ?string $serviceTypeKey = null
    ): ServicePointSearchRequestTransfer {
        $requestParameters = [
            static::SEARCH_REQUEST_PARAMETER_OFFSET => 0,
            static::SEARCH_REQUEST_PARAMETER_LIMIT => $searchResultLimit,
        ];

        if ($serviceTypeKey) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] = [$serviceTypeKey];
        }

        return (new ServicePointSearchRequestTransfer())->setRequestParameters($requestParameters);
    }
}
