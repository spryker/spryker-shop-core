<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Plugin;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\FileManagerWidget\FileManagerWidgetPluginInterface;

class FileManagerWidgetPlugin extends AbstractWidgetPlugin implements FileManagerWidgetPluginInterface
{
    /**
     * @param int $fileId
     *
     * @return void
     */
    public function initialize($fileId): void
    {
        $this->addParameter('fileId', $fileId);
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@FileManagerWidget/views/file_manager_widget/file-manager-widget.twig';
    }
}
