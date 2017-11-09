<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Plugin;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Silex\Application;
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
        return 'product_set';
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
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        $productSetStorageTransfer = $this->getFactory()->getProductSetClient()->mapProductSetStorageDataToTransfer($data);
        $storageProductTransfers = $this->mapStorageProducts($this->getApplication(), $productSetStorageTransfer);

        return [
            'productSetStorageTransfer' => $productSetStorageTransfer,
            'storageProductTransfers' => $storageProductTransfers,
        ];
    }

    /**
     * @param \Silex\Application $application
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function mapStorageProducts(Application $application, ProductSetStorageTransfer $productSetStorageTransfer)
    {
        $storageProductTransfers = [];
        foreach ($productSetStorageTransfer->getIdProductAbstracts() as $idProductAbstract) {
            $productAbstractData = $this->getFactory()->getProductClient()->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

            $storageProductTransfers[] = $this->getFactory()->getStorageProductMapperPlugin()->mapStorageProduct(
                $productAbstractData,
                $this->getSelectedAttributes($application, $idProductAbstract)
            );
        }

        return $storageProductTransfers;
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
