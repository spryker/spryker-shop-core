<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Dependency\Client;


class PriceWidgetToCustomerAccessStorageClientBridge implements PriceWidgetToCustomerAccessStorageClientInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageClientInterface
     */
    protected $customerAccessStorageClient;

    /**
     * @param \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageClientInterface $customerAccessStorageClient
     */
    public function __construct($customerAccessStorageClient)
    {
        $this->customerAccessStorageClient = $customerAccessStorageClient;
    }

    /**
	 * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($content)
    {
        return $this->customerAccessStorageClient->canUnauthenticatedCustomerAccessContentType($content);
    }
}
