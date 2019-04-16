<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ShareCartByLinkWidget extends AbstractWidget
{
    protected const PARAMETER_ID_QUOTE = 'idQuote';

    /**
     * @param int $idQuote
     */
    public function __construct(int $idQuote)
    {
        $this->addIdQuoteParameter($idQuote);
    }

    /**
     * @param int $idQuote
     *
     * @return void
     */
    protected function addIdQuoteParameter(int $idQuote): void
    {
        $this->addParameter(static::PARAMETER_ID_QUOTE, $idQuote);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShareCartByLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PersistentCartShareWidget/views/share-cart-by-link-widget/share-cart-by-link-widget.twig';
    }
}
