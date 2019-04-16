<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

class LinkController extends AbstractController
{
    /**
     * @param int $idQuote
     * @param string $permissionOption
     *
     * @return string
     */
    public function indexAction(int $idQuote, string $permissionOption): string
    {
        return 'http://www.de.suite-nonsplit.local';
    }
}
