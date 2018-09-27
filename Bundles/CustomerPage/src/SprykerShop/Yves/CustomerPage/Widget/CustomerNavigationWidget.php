<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerNavigationWidget extends AbstractWidget
{
    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     */
    public function __construct(string $activePage, ?int $activeEntityId = null)
    {
        $this->addParameter('activePage', $activePage)
            ->addParameter('activeEntityId', $activeEntityId);
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerNavigationWidget';
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerPage/views/customer-navigation/customer-navigation.twig';
    }
}
