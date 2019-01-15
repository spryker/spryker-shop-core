<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\AdditionalColumnsGetter;

class AdditionalColumnsGetter implements AdditionalColumnsGetterInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[]
     */
    protected $quickOrderFormColumnPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[] $quickOrderFormColumnPlugins
     */
    public function __construct(array $quickOrderFormColumnPlugins)
    {
        $this->quickOrderFormColumnPlugins = $quickOrderFormColumnPlugins;
    }

    /**
     * @return array
     */
    public function getAdditionalColumns(): array
    {
        $additionalColumns = [];
        foreach ($this->quickOrderFormColumnPlugins as $additionalColumnPlugin) {
            $additionalColumns[] = [
                'title' => $additionalColumnPlugin->getColumnTitle(),
                'dataPath' => $additionalColumnPlugin->getDataPath(),
            ];
        }

        return $additionalColumns;
    }
}
