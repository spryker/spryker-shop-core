<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class RedirectUrlRedirectAfterLoginStrategyPlugin extends AbstractPlugin implements CustomerRedirectStrategyPluginInterface
{
    protected const KEY_REQUEST = 'request';
    protected const PARAM_REDIRECT_URL = 'redirectUrl';

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
        return (bool)$this->findRedirectUrlFromRequest();
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
        $request = $this->getFactory()->getApplication()[static::KEY_REQUEST];
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL);

        return $redirectUrl;
    }
}
