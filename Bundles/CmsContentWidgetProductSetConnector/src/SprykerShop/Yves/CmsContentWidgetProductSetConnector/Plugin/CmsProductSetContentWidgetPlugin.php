<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector\Plugin;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Yves\CmsContentWidgetProductSetConnector\Plugin\CmsProductSetContentWidgetPlugin as SprykerCmsProductSetContentWidgetPlugin;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorFactory getFactory()
 */
class CmsProductSetContentWidgetPlugin extends SprykerCmsProductSetContentWidgetPlugin
{
    /**
     * @param \Twig\Environment $twig
     * @param array $context
     * @param array|string $productSetKeys
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Environment $twig, array $context, $productSetKeys, $templateIdentifier = null)
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createCmsProductContentWidgetCollection());

        $result = parent::contentWidgetFunction($twig, $context, $productSetKeys, $templateIdentifier);

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function createCmsProductContentWidgetCollection(): WidgetContainerInterface
    {
        return $this->getFactory()->createCmsProductSetContentWidgetCollection();
    }

    /**
     * @param array $context
     * @param array $productSetKeyMap
     * @param string $setKey
     *
     * @return array
     */
    protected function getSingleProductSet(array $context, array $productSetKeyMap, $setKey)
    {
        if (!isset($productSetKeyMap[$setKey])) {
            return [];
        }

        $productSet = $this->getProductSetStorageTransfer($productSetKeyMap[$setKey]);
        if (!$productSet || !$productSet->getIsActive()) {
            return [];
        }

        return [
            'productSet' => $productSet,
            'productViews' => $this->mapProductSetDataStorageTransfers($context, $productSet),
        ];
    }

    /**
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    protected function getProductSetStorageTransfer($idProductSet)
    {
        return $this->getFactory()->getProductSetStorageClient()->getProductSetByIdProductSet($idProductSet, $this->getLocale());
    }

    /**
     * @param array $context
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductSetDataStorageTransfers(array $context, ProductSetDataStorageTransfer $productSetDataStorageTransfer)
    {
        $selectedAttributes = [];
        foreach ($productSetDataStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $selectedAttributes[$idProductAbstract] = $this->getSelectedAttributes($context, $idProductAbstract);
        }

        $productViewTransfers = $this->getFactory()
            ->getProductStorageClient()
            ->getProductAbstractViewTransfers($productSetDataStorageTransfer->getProductAbstractIds(), $this->getLocale(), $selectedAttributes);

        return $productViewTransfers;
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
