<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CmsContentWidgetProductConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsProductGroupContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{

    const FUNCTION_NAME = 'product_group';

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array
     */
    public function getAvailableTemplates()
    {
        return [
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetProductConnector/_product-group/product-group-cms-content-widget.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ product_group(['sku1', 'sku2']) }}, to use different template {{ product_group(['sku1', 'sku2'], 'default') }}";
    }

}
