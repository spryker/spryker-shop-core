<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin;

interface ResourceCreatorPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getType();

    /**
     * @api
     *
     * @return string
     */
    public function getModuleName();

    /**
     * @api
     *
     * @return string
     */
    public function getControllerName();

    /**
     * @api
     *
     * @return string
     */
    public function getActionName();

    /**
     * @api
     *
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    public function mergeResourceData(array $data);
}
