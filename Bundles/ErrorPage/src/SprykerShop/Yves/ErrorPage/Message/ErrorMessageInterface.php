<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage\Message;

use Symfony\Component\Debug\Exception\FlattenException;

interface ErrorMessageInterface
{
    /**
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     *
     * @return string
     */
    public function getNotFoundMessage(FlattenException $exception): string;
}
