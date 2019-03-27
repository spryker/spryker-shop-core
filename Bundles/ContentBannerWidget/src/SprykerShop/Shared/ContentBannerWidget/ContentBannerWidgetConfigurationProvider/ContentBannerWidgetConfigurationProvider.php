<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\ContentBannerWidget\ContentBannerWidgetConfigurationProvider;

class ContentBannerWidgetConfigurationProvider implements ContentBannerWidgetConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'cms_banner';

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            ContentBannerWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@ContentBannerWidget/views/content-banner/content-banner.twig',
            ContentBannerWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@ContentBannerWidget/views/content-banner/content-banner.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ cms_banner(id) }}, to use different template {{ cms_product_list(id, 'default') }}";
    }
}