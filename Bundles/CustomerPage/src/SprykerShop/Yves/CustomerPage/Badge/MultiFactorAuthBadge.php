<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Badge;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class MultiFactorAuthBadge implements BadgeInterface
{
    /**
     * @uses \Spryker\Yves\MultiFactorAuth\Plugin\AuthenticationHandler\Customer\CustomerMultiFactorAuthenticationHandlerPlugin::CUSTOMER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME
     *
     * @var string
     */
    protected const CUSTOMER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'CUSTOMER_MULTI_FACTOR_AUTHENTICATION';

    /**
     * @var string
     */
    protected const PARAMETER_MULTI_FACTOR_AUTH_ENABLED = 'multi_factor_auth_enabled';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FORM_NAME
     *
     * @var string
     */
    protected const PARAMETER_LOGIN_FORM = 'loginForm';

    /**
     * @var bool
     */
    protected bool $isRequired = false;

    /**
     * @var bool
     */
    protected bool $isResolved = true;

    /**
     * @var int|null
     */
    protected ?int $status = null;

    /**
     * @param array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface> $customerMultiFactorAuthenticationHandlerPlugins
     */
    public function __construct(
        protected array $customerMultiFactorAuthenticationHandlerPlugins
    ) {
    }

    /**
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->isResolved;
    }

    /**
     * @param bool $isResolved
     *
     * @return void
     */
    public function setIsResolved(bool $isResolved): void
    {
        $this->isResolved = $isResolved;
    }

    /**
     * @param bool $isRequired
     *
     * @return void
     */
    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param int|null $status
     *
     * @return void
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function enable(CustomerTransfer $customerTransfer, Request $request)
    {
        foreach ($this->customerMultiFactorAuthenticationHandlerPlugins as $plugin) {
            if ($plugin->isApplicable(static::CUSTOMER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME) === false) {
                continue;
            }

            $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);
            $multiFactorAuthValidationResponseTransfer = $plugin->validateCustomerMultiFactorStatus($multiFactorAuthValidationRequestTransfer);

            if ($multiFactorAuthValidationResponseTransfer->getIsRequired() === true && $this->isRequestCorrupted($request)) {
                $this->setIsResolved(false);

                return $this;
            }

            $this->setIsRequired($multiFactorAuthValidationResponseTransfer->getIsRequired());
            $this->setStatus($multiFactorAuthValidationResponseTransfer->getStatus());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isRequestCorrupted(Request $request): bool
    {
        return !isset($request->request->all(static::PARAMETER_LOGIN_FORM)[static::PARAMETER_MULTI_FACTOR_AUTH_ENABLED]);
    }
}
