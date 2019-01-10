<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\FileOutputter;

interface FileOutputterInterface
{
    /**
     * @param string $fileType
     *
     * @return void
     */
    public function outputFile(string $fileType): void;
}
