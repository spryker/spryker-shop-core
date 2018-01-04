<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Creator;

interface ResourceCreatorHandlerInterface
{
    /**
     * @param string $type
     * @param array $data
     *
     * @return array|null
     */
    public function create($type, array $data);
}
