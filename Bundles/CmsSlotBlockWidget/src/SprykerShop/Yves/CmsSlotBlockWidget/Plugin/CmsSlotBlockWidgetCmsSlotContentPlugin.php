<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Plugin;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

/**
 * @method \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetFactory getFactory()
 */
class CmsSlotBlockWidgetCmsSlotContentPlugin extends AbstractPlugin implements CmsSlotContentPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        return $this->getFactory()
            ->createCmsSlotBlockWidgetDataProvider()
            ->getSlotContent($cmsSlotContentRequestTransfer);
    }
}
