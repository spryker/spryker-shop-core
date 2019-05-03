<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Glossary;

class GlossaryKeyGenerator implements GlossaryKeyGeneratorInterface
{
    /**
     * @param string $permissionGroup
     *
     * @return string
     */
    public function getKeyForPermissionGroup(string $permissionGroup): string
    {
        return 'persistent_cart_share.' . $permissionGroup . '_users';
    }

    /**
     * @param string $permissionGroup
     * @param string $permissionOption
     *
     * @return string
     */
    public function getKeyForPermissionOption(string $permissionGroup, string $permissionOption): string
    {
        return 'persistent_cart_share.share_options.' . $permissionOption;
    }
}
