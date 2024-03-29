<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CmsContentWidgetProductConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsProductContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const FUNCTION_NAME = 'product';

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array<string>
     */
    public function getAvailableTemplates()
    {
        return [
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetProductConnector/views/cms-product/cms-product.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ product(['sku1', 'sku2']) }}, to use different template {{ product(['sku1', 'sku2'], 'default') }}";
    }
}
