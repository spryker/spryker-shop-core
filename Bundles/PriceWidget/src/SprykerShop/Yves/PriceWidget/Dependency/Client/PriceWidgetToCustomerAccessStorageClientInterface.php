<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Dependency\Client;


interface PriceWidgetToCustomerAccessStorageClientInterface
{
    /**
	 * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($content);
}
