<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface PersistentCartShareWidgetToPersistentCartShareClientInterface
{
    /**
     * @return array
     */
    public function getCartShareOptions(): array;

    /**
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateCartResourceShare(int $idQuote, string $shareOption): ResourceShareResponseTransfer;
}
