<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CmsContentWidgetChartConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsChartContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'chart';

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return string[]
     */
    public function getAvailableTemplates(): array
    {
        return [
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetChartConnector/views/cms-chart-content/cms-chart-content.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ chart('plugin-name', 'data-identifier') }}, to use different template {{ chart('plugin-name', 'data-identifier', 'default') }}";
    }
}
