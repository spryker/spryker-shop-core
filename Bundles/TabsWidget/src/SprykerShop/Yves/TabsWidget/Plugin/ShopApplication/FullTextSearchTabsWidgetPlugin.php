<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TabsWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\TabMetaDataTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopApplication\Dependency\Plugin\TabsWidget\FullTextSearchTabsWidgetPluginInterface;

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
        $this->addParameter('tabs', $this->executeFullTextSearchPlugins($searchString, $activeTabName, $requestParams))
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
    protected function executeFullTextSearchPlugins(string $searchString, string $activeTabName, array $requestParams = []): array
    {
        $tabs = [];
        $fullTextSearchTabPlugins = $this->getFullTextSearchTabPluginsSe();

        foreach ($fullTextSearchTabPlugins as $fullTextSearchTabPlugin) {
            $tab = $this->createTab($fullTextSearchTabPlugin->getTabMetaData(), $activeTabName);
            $tab['count'] = !$tab['isActive'] ? $fullTextSearchTabPlugin->getTabCount($searchString, $requestParams) : null;
            if ($tab['isActive'] || $tab['count']) {
                $tabs[] = $tab;
            }
        }
        
        return $tabs;
    }

    /**
     * @return array
     */
    protected function getFullTextSearchTabPluginsSe(): array
    {
        return $this->getFactory()->getFullTextSearchTabPlugins();
    }

    /**
     * @param \Generated\Shared\Transfer\TabMetaDataTransfer $tabMetaDataTransfer
     * @param string $activeTabName
     *
     * @return array
     */
    protected function createTab(TabMetaDataTransfer $tabMetaDataTransfer, string $activeTabName): array
    {
        $tab = $tabMetaDataTransfer->toArray();
        $tab['isActive'] = $tabMetaDataTransfer->getName() === $activeTabName;
        return $tab;
    }
}
