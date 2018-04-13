<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use Generated\Shared\Transfer\ShareCartRequestTransfer;

interface ShareCartFormDataProviderInterface
{
    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    public function getData($idQuote): ShareCartRequestTransfer;

    /**
     * @return array
     */
    public function getOptions(): array;
}
