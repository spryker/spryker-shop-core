<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationWidget\Plugin\CompanyPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CompanyPage\Dependency\Plugin\CompanyUserInvitationWidget\CompanyUserInvitationWidgetPluginInterface;

class CompanyUserInvitationWidgetPlugin extends AbstractWidgetPlugin implements CompanyUserInvitationWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyUserInvitationWidget/views/company-user-invitation-link/company-user-invitation-link.twig';
    }
}
