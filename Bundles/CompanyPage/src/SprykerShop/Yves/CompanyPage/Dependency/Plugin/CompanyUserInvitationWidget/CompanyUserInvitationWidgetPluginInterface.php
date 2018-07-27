<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Plugin\CompanyUserInvitationWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CompanyUserInvitationWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CompanyUserInvitationWidgetPlugin';

    /**
     * @return void
     */
    public function initialize(): void;
}
