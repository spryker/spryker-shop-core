<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\FileWidget;

interface FileWidgetPluginInterface
{
    const NAME = 'FileWidgetPlugin';

    /**
     * @param int $fileId
     *
     * @return void
     */
    public function initialize($fileId): void;

}
