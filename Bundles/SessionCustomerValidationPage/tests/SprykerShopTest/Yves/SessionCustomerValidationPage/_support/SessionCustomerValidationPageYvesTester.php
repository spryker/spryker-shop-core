<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class SessionCustomerValidationPageYvesTester extends Actor
{
    use _generated\SessionCustomerValidationPageYvesTesterActions;

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig::SESSION_ENTITY_TYPE
     *
     * @var string
     */
    protected const SESSION_ENTITY_TYPE = 'customer';

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface
     */
    public function createCustomerClientMock(?CustomerTransfer $customerTransfer = null): SessionCustomerValidationPageToCustomerClientInterface
    {
        return Stub::makeEmpty(
            SessionCustomerValidationPageToCustomerClientInterface::class,
            [
                'getCustomer' => $customerTransfer,
                'getCustomerByEmail' => $customerTransfer ?? new CustomerTransfer(),
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    public function createCustomerSessionSaverPluginMock(): CustomerSessionSaverPluginInterface
    {
        return Stub::makeEmpty(CustomerSessionSaverPluginInterface::class);
    }

    /**
     * @param bool $isSessionValid
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface
     */
    public function createCustomerSessionValidatorPluginMock(bool $isSessionValid = false): CustomerSessionValidatorPluginInterface
    {
        return Stub::makeEmpty(
            CustomerSessionValidatorPluginInterface::class,
            [
                'validate' => (new SessionEntityResponseTransfer())->setIsSuccessfull($isSessionValid),
            ],
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig
     */
    public function createConfigMock(): SessionCustomerValidationPageConfig
    {
        return Stub::makeEmpty(
            SessionCustomerValidationPageConfig::class,
            [
                'getSessionEntityType' => static::SESSION_ENTITY_TYPE,
            ],
        );
    }

    /**
     * @param bool $withUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createAuthenticationTokenMock(bool $withUser = false): TokenInterface
    {
        $userMock = null;
        if ($withUser) {
            $userMock = Stub::makeEmpty(UserInterface::class);
        }

        return Stub::makeEmpty(
            TokenInterface::class,
            [
                'getUser' => $userMock,
            ],
        );
    }
}
