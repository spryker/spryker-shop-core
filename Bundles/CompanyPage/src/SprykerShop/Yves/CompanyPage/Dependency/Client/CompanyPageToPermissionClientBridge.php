<?php

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class CompanyPageToPermissionClientBridge implements CompanyPageToPermissionClientInterface
{
    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     */
    public function __construct($permissionClient)
    {
        $this->permissionClient = $permissionClient;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer
    {
        return $this->permissionClient->findAll();
    }
}
