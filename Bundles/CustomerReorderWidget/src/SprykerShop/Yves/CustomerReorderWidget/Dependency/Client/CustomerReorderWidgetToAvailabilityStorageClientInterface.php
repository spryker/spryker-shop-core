<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 8/2/18
 * Time: 10:39
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

use Generated\Shared\Transfer\StorageAvailabilityTransfer;

interface CustomerReorderWidgetToAvailabilityStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @return StorageAvailabilityTransfer|null
     */
    public function getProductAvailabilityByIdProductAbstract(int $idProductAbstract): ?StorageAvailabilityTransfer;
}
