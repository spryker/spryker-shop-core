<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Handler;

interface CartNoteHandlerInterface
{
    /**
     * @param string $note
     *
     * @return void
     */
    public function setNoteToQuote($note);

    /**
     * @param string $note
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function setNoteToQuoteItem($note, $sku, $groupKey = null);
}
