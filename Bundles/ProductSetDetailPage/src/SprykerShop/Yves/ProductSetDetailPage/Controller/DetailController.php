<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Controller;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Pyz\Yves\Application\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class DetailController extends AbstractController
{

    const PARAM_ATTRIBUTE = 'attributes';

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     * @param \Generated\Shared\Transfer\StorageProductTransfer[] $storageProductTransfers
     *
     * @return array
     */
    public function indexAction(ProductSetStorageTransfer $productSetStorageTransfer, array $storageProductTransfers)
    {
        $data = [
            'productSet' => $productSetStorageTransfer,
            'storageProducts' => $storageProductTransfers,
        ];

        return $this->view($data, $this->getFactory()->getProductSetDetailPageWidgetPlugins());
    }

}
