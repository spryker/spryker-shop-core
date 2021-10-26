<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ContentNavigationWidgetConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE
     *
     * @var string
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE = 'tree-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE
     *
     * @var string
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TREE = 'tree';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE
     *
     * @var string
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE = 'list-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST
     *
     * @var string
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST = 'list';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAvailableTemplateList(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE => '@ContentNavigationWidget/views/navigation/tree-inline.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE => '@ContentNavigationWidget/views/navigation/tree.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE => '@ContentNavigationWidget/views/navigation/list-inline.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST => '@ContentNavigationWidget/views/navigation/list.twig',
        ];
    }
}
