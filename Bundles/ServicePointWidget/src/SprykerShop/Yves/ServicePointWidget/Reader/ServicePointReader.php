<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Reader;

use Generated\Shared\Transfer\ServicePointSearchCollectionTransfer;
use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface;
use Twig\Environment;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @var string
     *
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter\ServicePointSearchResultFormatterPlugin::NAME
     */
    protected const RESULT_FORMATTER = 'ServicePointSearchCollection';

    /**
     * @var string
     *
     * @uses \SprykerShop\Yves\ServicePointWidget\Plugin\Router\ServicePointWidgetRouteProviderPlugin::ROUTE_NAME_SEARCH
     */
    protected const ROUTE_NAME_SEARCH = 'service-point-widget/search';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID = 'serviceTypeUuid';

    /**
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID = 'shipmentTypeUuid';

    /**
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_ITEM_GROUP_KEYS = 'itemGroupKeys';

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
     * @var \SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface
     */
    protected ServicePointWidgetToServicePointSearchClientInterface $servicePointSearchClient;

    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @param \SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface $servicePointSearchClient
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        ServicePointWidgetToServicePointSearchClientInterface $servicePointSearchClient,
        Environment $twigEnvironment
    ) {
        $this->servicePointSearchClient = $servicePointSearchClient;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return string
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): string
    {
        $searchResults = $this->servicePointSearchClient->searchServicePoints($servicePointSearchRequestTransfer);
        $servicePointSearchCollectionTransfer = $searchResults[static::RESULT_FORMATTER] ?? new ServicePointSearchCollectionTransfer();
        $requestParameters = $servicePointSearchRequestTransfer->getRequestParameters();
        $serviceTypeKeys = $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] ?? null;
        $serviceTypeUuid = $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPE_UUID] ?? null;
        $shipmentTypeUuid = $requestParameters[static::SEARCH_REQUEST_PARAMETER_SHIPMENT_TYPE_UUID] ?? null;
        $itemGroupKeys = $requestParameters[static::SEARCH_REQUEST_PARAMETER_ITEM_GROUP_KEYS] ?? [];

        return $this->twigEnvironment->render(
            '@ServicePointWidget/views/service-point-list/service-point-list.twig',
            [
                'servicePoints' => $servicePointSearchCollectionTransfer->getServicePoints()->getArrayCopy(),
                'nbResults' => $servicePointSearchCollectionTransfer->getNbResultsOrFail(),
                'offset' => $requestParameters[static::SEARCH_REQUEST_PARAMETER_OFFSET],
                'limit' => $requestParameters[static::SEARCH_REQUEST_PARAMETER_LIMIT],
                'serviceTypeKey' => $serviceTypeKeys ? reset($serviceTypeKeys) : null,
                'serviceTypeUuid' => $serviceTypeUuid,
                'shipmentTypeUuid' => $shipmentTypeUuid,
                'itemGroupKeys' => $itemGroupKeys,
                'searchString' => $servicePointSearchRequestTransfer->getSearchString(),
                'searchRoute' => static::ROUTE_NAME_SEARCH,
            ],
        );
    }
}
