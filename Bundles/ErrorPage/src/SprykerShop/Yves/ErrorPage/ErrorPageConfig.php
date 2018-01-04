<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class ErrorPageConfig extends AbstractBundleConfig
{
    /**
     * @return int[]
     */
    public function getValidSubRequestExceptionStatusCodes()
    {
        return [
            Response::HTTP_NOT_FOUND,
        ];
    }
}
