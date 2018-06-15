<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\FileManagerWidget\CmsContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class FileManagerWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    const FUNCTION_NAME = 'cms_file';

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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@FileManagerWidget/views/file_manager_widget/file-manager-widget.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ cms_file('fileId') }}";
    }
}
