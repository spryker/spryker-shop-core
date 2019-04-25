<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ResourceSharePage\ResourceSharePageFactory getFactory()
 */
class ResourceSharePageRedirectAfterLoginStrategyPlugin extends AbstractPlugin implements CustomerRedirectStrategyPluginInterface
{
    protected const KEY_APPLICATION_REQUEST = 'request';

    /**
     * @see \SprykerShop\Yves\ResourceSharePage\Controller\LinkController::BACK_TO_LINK_REDIRECT
     */
    protected const BACK_TO_LINK_REDIRECT = 'backToLinkRedirect';

    /**
     * {@inheritdoc}
     * - Checks if application request has Resource Share Redirect Url.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerTransfer $customerTransfer): bool
    {
        $redirectUrl = $this->findRedirectUrlFromRequest();

        return (bool)$redirectUrl;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function getRedirectUrl(CustomerTransfer $customerTransfer): string
    {
        return $this->findRedirectUrlFromRequest();
    }

    /**
     * @return string|null
     */
    protected function findRedirectUrlFromRequest(): ?string
    {
        $request = $this->getFactory()->getApplication()[static::KEY_APPLICATION_REQUEST];
        $redirectUrl = $request->get(static::BACK_TO_LINK_REDIRECT);

        return $redirectUrl;
    }
}
