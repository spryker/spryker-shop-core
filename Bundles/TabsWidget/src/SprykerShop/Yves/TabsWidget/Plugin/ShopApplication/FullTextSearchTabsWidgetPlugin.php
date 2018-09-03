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
            ->addParameter('requestParams', array_merge($requestParams, ['q' => $searchString]));
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
        $fulltextSearchPlugins = $this->getFactory()->getFullTextSearchPlugins();
        foreach ($fulltextSearchPlugins as $fullTextSearchPlugin) {
            $metaData = $fullTextSearchPlugin->getTabMetaData();
            $isCurrentTabActive = $metaData->getName() === $activeTabName;
            $tab = $metaData->toArray();
            $tab['isActive'] = $isCurrentTabActive;
            if (!$isCurrentTabActive) {
                $tab['count'] = $fullTextSearchPlugin->getTabCount($searchString, $requestParams);
                if (!$tab['count']) {
                    continue;
                }
            }
            $tabs[] = $tab;
        }
        return $tabs;
    }
}
