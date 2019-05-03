<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Glossary;

class GlossaryKeyGenerator implements GlossaryKeyGeneratorInterface
{
    /**
     * @param string $shareOptionGroupName
     *
     * @return string
     */
    public function getShareOptionGroupKey(string $shareOptionGroupName): string
    {
        return 'persistent_cart_share.' . $shareOptionGroupName . '_users';
    }

    /**
     * @param string $shareOptionGroup
     * @param string $shareOption
     *
     * @return string
     */
    public function getShareOptionKey(string $shareOptionGroup, string $shareOption): string
    {
        return 'persistent_cart_share.share_options.' . $shareOption;
    }
}
