<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\FileManagerWidget;

interface FileManagerWidgetPluginInterface
{
    const NAME = 'FileManagerWidgetPlugin';

    /**
     * @param int $fileId
     *
     * @return void
     */
    public function initialize($fileId): void;
}
