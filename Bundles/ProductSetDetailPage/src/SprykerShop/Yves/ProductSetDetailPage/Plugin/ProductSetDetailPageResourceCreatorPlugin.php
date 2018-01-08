<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Plugin;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Silex\Application;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductSetDetailPage\ProductSetDetailPageFactory getFactory()
 */
class ProductSetDetailPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'ProductSetDetailPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Detail';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'index';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        $productSetStorageTransfer = $this->getFactory()->getProductSetStorageClient()->mapProductSetStorageDataToTransfer($data);
        $productViewTransfers = $this->mapProductViewTransfer($this->getApplication(), $productSetStorageTransfer);

        return [
            'productSetDataStorageTransfer' => $productSetStorageTransfer,
            'productViewTransfers' => $productViewTransfers,
        ];
    }

    /**
     * @param \Silex\Application $application
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductViewTransfer(Application $application, ProductSetDataStorageTransfer $productSetDataStorageTransfer)
    {
        $productViewTransfers = [];
        foreach ($productSetDataStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $productAbstractData = $this->getFactory()->getProductStorageClient()->getProductAbstractStorageData($idProductAbstract, $this->getLocale());

            $productViewTransfers[] = $this->getFactory()->getProductStorageClient()->mapProductStorageData(
                $productAbstractData,
                $this->getLocale(),
                $this->getSelectedAttributes($application, $idProductAbstract)
            );
        }

        return $productViewTransfers;
    }

    /**
     * @param \Silex\Application $application
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getSelectedAttributes(Application $application, $idProductAbstract)
    {
        $attributes = $this->getRequest($application)->query->get(DetailController::PARAM_ATTRIBUTE, []);

        return isset($attributes[$idProductAbstract]) ? array_filter($attributes[$idProductAbstract]) : [];
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(Application $application)
    {
        return $application['request'];
    }
}
