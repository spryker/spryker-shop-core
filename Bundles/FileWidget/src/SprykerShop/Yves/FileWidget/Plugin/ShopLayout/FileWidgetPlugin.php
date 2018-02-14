<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\FileWidget\FileWidgetPluginInterface;

/**
 * Class FileWidgetPlugin
 *
 * @method \SprykerShop\Yves\FileWidget\FileWidgetFactory getFactory()
 */
class FileWidgetPlugin extends AbstractWidgetPlugin implements FileWidgetPluginInterface
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
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@FileWidget/_file/_main.twig';
    }

}
