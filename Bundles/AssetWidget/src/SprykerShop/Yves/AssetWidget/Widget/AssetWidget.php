<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget\Widget;

use Generated\Shared\Transfer\AssetSlotContentRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\AssetWidget\AssetWidgetFactory getFactory()
 */
class AssetWidget extends AbstractWidget
{
    /**
     * @param string $slotKey
     */
    public function __construct(string $slotKey)
    {
        $this->addParameter('assetSlotContent', $this->getAssetSlotContent($slotKey));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AssetWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AssetWidget/views/asset/asset.twig';
    }

    /**
     * @param string $slotKey
     *
     * @return string
     */
    protected function getAssetSlotContent(string $slotKey): string
    {
        $assetSlotContentRequestTransfer = (new AssetSlotContentRequestTransfer())
            ->setAssetSlot($slotKey);

        return (string)$this->getFactory()
            ->createAssetWidgetDataProvider()
            ->getSlotContent($assetSlotContentRequestTransfer)
            ->getContent();
    }
}
