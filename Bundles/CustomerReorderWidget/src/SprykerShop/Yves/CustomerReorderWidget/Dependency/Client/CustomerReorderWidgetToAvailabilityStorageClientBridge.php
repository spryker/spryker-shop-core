<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 8/2/18
 * Time: 10:36
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;


use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Spryker\Client\Availability\AvailabilityClientInterface;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface;

class CustomerReorderWidgetToAvailabilityStorageClientBridge implements CustomerReorderWidgetToAvailabilityStorageClientInterface
{
    /**
     * @var AvailabilityStorageClientInterface
     */
    protected $availabilityClient;

    /**
     * @param AvailabilityStorageClientInterface $availabilityClient
     */
    public function __construct($availabilityClient)
    {
        $this->availabilityClient = $availabilityClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return StorageAvailabilityTransfer|null
     */
    public function getProductAvailabilityByIdProductAbstract(int $idProductAbstract): ?StorageAvailabilityTransfer
    {
        return $this->availabilityClient->getProductAvailabilityByIdProductAbstract($idProductAbstract);
    }
}
