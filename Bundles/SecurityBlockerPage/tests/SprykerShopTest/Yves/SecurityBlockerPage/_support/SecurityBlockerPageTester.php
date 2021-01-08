<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SecurityBlockerPage;

use Codeception\Actor;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SecurityBlockerPageTester extends Actor
{
    use _generated\SecurityBlockerPageTesterActions;

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    public function getSecurityCheckAuthContextTransfer(string $type): SecurityCheckAuthContextTransfer
    {
        return (new SecurityCheckAuthContextTransfer())
            ->setType($type)
            ->setAccount('test@spryker.com')
            ->setIp('66.66.66.6');
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): Request
    {
        $route = 'login_check';

        if ($securityCheckAuthContextTransfer->getType() === SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE) {
            $route = 'agent_login_check';
        }

        $request = Request::create(
            '/' . $route,
            Request::METHOD_POST,
            [
                'loginForm' => [
                    'email' => $securityCheckAuthContextTransfer->getAccount(),
                ],
            ],
            [],
            [],
            [
                'REMOTE_ADDR' => $securityCheckAuthContextTransfer->getIp(),
            ]
        );
        $request->attributes->add(['_route' => $route]);

        return $request;
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getInvalidRequest(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): Request
    {
        $route = 'login_check';

        if ($securityCheckAuthContextTransfer->getType() === SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE) {
            $route = 'agent_login_check';
        }

        $request = Request::create(
            '/' . $route,
            Request::METHOD_GET,
            [
                'loginForm' => [
                    'email' => $securityCheckAuthContextTransfer->getAccount(),
                ],
            ],
            [],
            [],
            [
                'REMOTE_ADDR' => $securityCheckAuthContextTransfer->getIp(),
            ]
        );
        $request->attributes->add(['_route' => $route]);

        return $request;
    }
}
