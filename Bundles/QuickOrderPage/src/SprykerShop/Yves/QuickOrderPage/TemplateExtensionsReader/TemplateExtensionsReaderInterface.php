<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\TemplateExtensionsReader;

interface TemplateExtensionsReaderInterface
{
    /**
     * @return string[]
     */
    public function getTemplateExtensions(): array;
}
