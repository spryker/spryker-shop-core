<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

class ProductConfiguratorResponseDataMapper implements ProductConfiguratorResponseDataMapperInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     */
    public function mapRequestToProductConfiguratorResponse(
        Request $request,
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseTransfer {
        $data = json_decode($request->getContent(), true) ?? [];

        $productConfiguratorResponseTransfer
            ->fromArray($data, true)
            ->setProductConfigurationInstance((new ProductConfigurationInstanceTransfer())->fromArray($data, true));

        return $productConfiguratorResponseTransfer;
    }
}
