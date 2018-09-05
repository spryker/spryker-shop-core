<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TabsWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\FullTextSearchTabTransfer;
use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopApplication\Dependency\Plugin\TabsWidget\FullTextSearchTabsWidgetPluginInterface;
use SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface;

/**
 * @method \SprykerShop\Yves\TabsWidget\TabsWidgetFactory getFactory()
 */
class FullTextSearchTabsWidgetPlugin extends AbstractWidgetPlugin implements FullTextSearchTabsWidgetPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@TabsWidget/views/tabs/tabs.twig';
    }

    /**
     * @param string $searchString
     * @param string $activeTabName
     * @param array $requestParams
     *
     * @return void
     */
    public function initialize(string $searchString, string $activeTabName, array $requestParams = []): void
    {
        $this->addParameter('tabs', $this->getTabs($searchString, $activeTabName, $requestParams))
            ->addParameter('searchString', $searchString)
            ->addParameter('requestParams', $requestParams);
    }

    /**
     * @param string $searchString
     * @param string $activeTabName
     * @param array $requestParams
     *
     * @return array
     */
    protected function getTabs(string $searchString, string $activeTabName, array $requestParams = []): array
    {
        $fullTextSearchTabPlugins = $this->getFullTextSearchTabPlugins();
        $tabs = [];

        foreach ($fullTextSearchTabPlugins as $fullTextSearchTabPlugin) {
            $metaData = $fullTextSearchTabPlugin->getTabMetaData();
            $tab = $this->createTab($metaData, $metaData->getName() === $activeTabName);

            if (!$tab->getIsActive()) {
                $tab->setCount($this->getTabCount($fullTextSearchTabPlugin, $searchString, $requestParams));
            }

            if ($tab->getIsActive() || $tab->getCount()) {
                $tabs[] = $tab;
            }
        }

        return $tabs;
    }

    /**
     * @return array
     */
    protected function getFullTextSearchTabPlugins(): array
    {
        return $this->getFactory()->getFullTextSearchTabPlugins();
    }

    /**
     * @param \Generated\Shared\Transfer\TabMetaDataTransfer $tabMetaDataTransfer
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\FullTextSearchTabTransfer
     */
    protected function createTab(TabMetaDataTransfer $tabMetaDataTransfer, bool $isActive): FullTextSearchTabTransfer
    {
        $fullTextTabTransfer = (new FullTextSearchTabTransfer());

        $fullTextTabTransfer->fromArray($tabMetaDataTransfer->toArray(), true);
        $fullTextTabTransfer->setIsActive($isActive);

        return $fullTextTabTransfer;
    }

    /**
     * @param \SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface $fullTextSearchTabPlugin
     * @param string $searchString
     * @param array $requestParams
     *
     * @return int
     */
    protected function getTabCount(
        FullTextSearchTabPluginInterface $fullTextSearchTabPlugin,
        string $searchString,
        array $requestParams = []
    ): int {
        return $fullTextSearchTabPlugin->calculateItemCount($searchString, $requestParams);
    }
}
