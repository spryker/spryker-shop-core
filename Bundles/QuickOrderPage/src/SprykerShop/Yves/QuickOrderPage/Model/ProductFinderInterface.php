<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 2/28/18
 * Time: 10:49
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

interface ProductFinderInterface
{
    /**
     * @param string $searchString
     * @param string|null $searchField
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getSearchResults(string $searchString, string $searchField = null, int $limit = null): array;
}
