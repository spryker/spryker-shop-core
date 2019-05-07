<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

interface CodeHandlerInterface
{
    /**
     * @param string $code
     *
     * @return void
     */
    public function add($code);

    /**
     * @param string $code
     *
     * @return void
     */
    public function remove($code);

    /**
     * @return void
     */
    public function clear();
}
