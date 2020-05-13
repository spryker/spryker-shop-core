<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Resolver;

use Generated\Shared\Transfer\ShopContextTransfer;
use Spryker\Yves\Kernel\Application;

class ShopContextResolver implements ShopContextResolverInterface
{
    protected const SERVICE_SHOP_CONTEXT = 'SERVICE_SHOP_CONTEXT';

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return \Generated\Shared\Transfer\ShopContextTransfer
     */
    public function resolve(): ShopContextTransfer
    {
        return $this->application[static::SERVICE_SHOP_CONTEXT] ?? new ShopContextTransfer();
    }
}
