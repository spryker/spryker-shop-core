<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Twig;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Twig\TwigExtension;
use SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface;
use Twig\TwigFunction;

class CatalogPageTwigExtension extends TwigExtension
{
    /**
     * @var string
     */
    public const FUNCTION_GET_URL_WITHOUT_ACTIVE_SEARCH_FILTER = 'generateUrlWithoutActiveSearchFilter';

    /**
     * @var string
     */
    public const FUNCTION_GET_URL_WITHOUT_ALL_ACTIVE_SEARCH_FILTERS = 'generateUrlWithoutAllActiveSearchFilters';

    /**
     * @var \SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface
     */
    protected $activeSearchFilterUrlGenerator;

    /**
     * @param \SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface $activeSearchFilterUrlGenerator
     */
    public function __construct(UrlGeneratorInterface $activeSearchFilterUrlGenerator)
    {
        $this->activeSearchFilterUrlGenerator = $activeSearchFilterUrlGenerator;
    }

    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(static::FUNCTION_GET_URL_WITHOUT_ACTIVE_SEARCH_FILTER, [$this, static::FUNCTION_GET_URL_WITHOUT_ACTIVE_SEARCH_FILTER], [
                'needs_context' => true,
                'is_safe' => ['html'],
            ]),
            new TwigFunction(static::FUNCTION_GET_URL_WITHOUT_ALL_ACTIVE_SEARCH_FILTERS, [$this, static::FUNCTION_GET_URL_WITHOUT_ALL_ACTIVE_SEARCH_FILTERS], [
                'needs_context' => true,
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @param array<string, mixed> $context
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer $searchResultTransfer
     * @param string $filterValue
     *
     * @return string
     */
    public function generateUrlWithoutActiveSearchFilter(array $context, TransferInterface $searchResultTransfer, $filterValue)
    {
        $request = $this->getRequestFromContext($context);

        return $this->activeSearchFilterUrlGenerator->generateUrlWithoutActiveSearchFilter($request, $searchResultTransfer, $filterValue);
    }

    /**
     * @param array<string, mixed> $context
     * @param array<\Generated\Shared\Transfer\FacetSearchResultTransfer|\Generated\Shared\Transfer\RangeSearchResultTransfer> $facetFilters
     *
     * @return string
     */
    public function generateUrlWithoutAllActiveSearchFilters($context, array $facetFilters)
    {
        $request = $this->getRequestFromContext($context);

        return $this->activeSearchFilterUrlGenerator->generateUrlWithoutAllActiveSearchFilters($request, $facetFilters);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestFromContext(array $context)
    {
        return $context['app']['request'];
    }
}
