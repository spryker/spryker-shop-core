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
class RedirectUriCustomerRedirectStrategyPlugin extends AbstractPlugin implements CustomerRedirectStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\Application\Application::SERVICE_REQUEST
     */
    protected const REQUEST = 'request';
    protected const PARAM_REDIRECT_URI = 'redirectUri';

    /**
     * {@inheritDoc}
     * - Checks if application request has param Redirect Uri.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerTransfer $customerTransfer): bool
    {
        return (bool)$this->findParamRedirectUri();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function getRedirectUrl(CustomerTransfer $customerTransfer): string
    {
        return $this->findParamRedirectUri();
    }

    /**
     * @return string|null
     */
    protected function findParamRedirectUri(): ?string
    {
        $request = $this->getFactory()->getApplication()[static::REQUEST];

        return $request->get(static::PARAM_REDIRECT_URI);
    }
}
