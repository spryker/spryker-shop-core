<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface;

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
 * @SuppressWarnings(\SprykerShopTest\Yves\SessionAgentValidation\PHPMD)
 */
class SessionAgentValidationYvesTester extends Actor
{
    use _generated\SessionAgentValidationYvesTesterActions;

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig::SESSION_ENTITY_TYPE
     *
     * @var string
     */
    protected const SESSION_ENTITY_TYPE = 'agent';

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $userTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidation\Dependency\Client\SessionAgentValidationToAgentClientInterface
     */
    public function createAgentClientMock(?UserTransfer $userTransfer = null): SessionAgentValidationToAgentClientInterface
    {
        return Stub::makeEmpty(
            SessionAgentValidationToAgentClientInterface::class,
            [
                'findAgentByUsername' => $userTransfer,
                'isLoggedIn' => $userTransfer !== null,
                'getAgent' => $userTransfer,
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentSaverPluginInterface
     */
    public function createSessionAgentSaverPluginMock(): SessionAgentSaverPluginInterface
    {
        return Stub::makeEmpty(SessionAgentSaverPluginInterface::class);
    }

    /**
     * @param bool $isSessionValid
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface
     */
    public function createSessionAgentValidatorPluginMock(bool $isSessionValid = false): SessionAgentValidatorPluginInterface
    {
        return Stub::makeEmpty(
            SessionAgentValidatorPluginInterface::class,
            [
                'validate' => (new SessionEntityResponseTransfer())->setIsSuccessfull($isSessionValid),
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig
     */
    public function createConfigMock(): SessionAgentValidationConfig
    {
        return Stub::makeEmpty(
            SessionAgentValidationConfig::class,
            [
                'getSessionEntityType' => static::SESSION_ENTITY_TYPE,
            ],
        );
    }
}
