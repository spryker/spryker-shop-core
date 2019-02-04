<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Renderer;

use Symfony\Component\HttpFoundation\Response;

interface FileRendererInterface
{
    /**
     * @param string $fileType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(string $fileType): Response;
}
