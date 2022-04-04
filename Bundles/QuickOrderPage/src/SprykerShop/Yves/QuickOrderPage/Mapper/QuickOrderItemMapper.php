<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Mapper;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Symfony\Component\HttpFoundation\Request;

class QuickOrderItemMapper implements QuickOrderItemMapperInterface
{
    /**
     * @var array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemMapperPluginInterface>
     */
    protected $quickOrderItemMapperPlugins;

    /**
     * @param array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemMapperPluginInterface> $quickOrderItemMapperPlugins
     */
    public function __construct(array $quickOrderItemMapperPlugins)
    {
        $this->quickOrderItemMapperPlugins = $quickOrderItemMapperPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function mapRequestToQuickOrderItemTransfer(
        Request $request,
        QuickOrderItemTransfer $quickOrderItemTransfer
    ): QuickOrderItemTransfer {
        $sku = (string)$request->query->get(QuickOrderItemTransfer::SKU);
        $quickOrderItemTransfer->setSku($sku);

        $queryParams = $request->query->all();

        foreach ($this->quickOrderItemMapperPlugins as $quickOrderItemMapperPlugin) {
            $quickOrderItemTransfer = $quickOrderItemMapperPlugin->map($quickOrderItemTransfer, $queryParams);
        }

        return $quickOrderItemTransfer;
    }
}
