<?php

namespace SprykerShop\Yves\TabsWidget\Plugin\ShopApplication;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopApplication\Dependency\Plugin\TabsWidget\FullTextSearchTabsWidgetPluginInterface;

/**
 * Class FullTextSearchTabsWidgetPlugin
 * @package SprykerShop\Yves\TabsWidget\Plugin\ShopApplication
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
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function executeFullTextSearchPlugins(string $searchString, string $activeTabName, array $requestParams = []): array
    {
        $tabs = [];
        foreach ($this->getFactory()->getFullTextSearchPlugins() as $fullTextSearchPlugin) {
            $metaData = $fullTextSearchPlugin->getTabMetaData();
            $isActive = $metaData->getName() === $activeTabName;
            $tab = $metaData->toArray();
            $tab['isActive'] = $isActive;
            if (!$isActive) {
                $tab['count'] = $fullTextSearchPlugin->calculateItemCount($searchString, $requestParams);
                if (!$tab['count']) {
                    continue;
                }
            }
            $tabs[] = $tab;
        }
        return $tabs;
    }
}
