<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector\Plugin;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Yves\CmsContentWidgetProductSetConnector\Plugin\CmsProductSetContentWidgetPlugin as SprykerCmsProductSetContentWidgetPlugin;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorFactory getFactory()
 */
class CmsProductSetContentWidgetPlugin extends SprykerCmsProductSetContentWidgetPlugin
{

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param array|string $productSetKeys
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, $productSetKeys, $templateIdentifier = null)
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createView());

        $result = parent::contentWidgetFunction($twig, $context, $productSetKeys, $templateIdentifier);

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function createView(): View
    {
        return new View([], $this->getFactory()->getCmsProductSetContentWidgetPlugins());
    }

    /**
     * @param array $context
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function mapStorageProducts(array $context, ProductSetStorageTransfer $productSetStorageTransfer)
    {
        $storageProductTransfers = [];
        foreach ($productSetStorageTransfer->getIdProductAbstracts() as $idProductAbstract) {
            $productAbstractData = $this->getFactory()->getProductClient()->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

            $storageProductTransfers[] = $this->getFactory()->getStorageMapperPlugin()->mapStorageProduct(
                $productAbstractData,
                $this->getSelectedAttributes($context, $idProductAbstract)
            );
        }

        return $storageProductTransfers;
    }

    /**
     * @param array $context
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getSelectedAttributes(array $context, $idProductAbstract)
    {
        $attributes = $this->getRequest($context)->query->get(DetailController::PARAM_ATTRIBUTE, []);

        return isset($attributes[$idProductAbstract]) ? array_filter($attributes[$idProductAbstract]) : [];
    }

    /**
     * @param array $context
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(array $context)
    {
        return $context['app']['request'];
    }

}
