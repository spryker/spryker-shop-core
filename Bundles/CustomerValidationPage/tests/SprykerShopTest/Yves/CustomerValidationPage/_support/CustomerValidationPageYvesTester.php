<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerValidationPage;

use Codeception\Actor;
use Codeception\Stub;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use SprykerShop\Yves\CustomerValidationPage\Plugin\ShopApplication\LogoutInvalidatedCustomerFilterControllerEventHandlerPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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
class CustomerValidationPageYvesTester extends Actor
{
    use _generated\CustomerValidationPageYvesTesterActions;

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE = 'TEST--1';

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE);
    }

    /**
     * @param \DateTime|null $anonymizedAt
     * @param \DateTime|null $passwordUpdatedAt
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollectionTransfer(
        ?DateTime $anonymizedAt,
        ?DateTime $passwordUpdatedAt
    ): InvalidatedCustomerCollectionTransfer {
        $invalidatedCustomerTransfer = (new InvalidatedCustomerTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE);

        if ($anonymizedAt !== null) {
            $invalidatedCustomerTransfer->setAnonymizedAt(
                $anonymizedAt->format('Y-m-d H:i:s'),
            );
        }

        if ($passwordUpdatedAt !== null) {
            $invalidatedCustomerTransfer->setPasswordUpdatedAt(
                $passwordUpdatedAt->format('Y-m-d H:i:s'),
            );
        }

        return (new InvalidatedCustomerCollectionTransfer())
            ->addInvalidatedCustomer($invalidatedCustomerTransfer);
    }

    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Plugin\ShopApplication\LogoutInvalidatedCustomerFilterControllerEventHandlerPlugin
     */
    public function createLogoutInvalidatedCustomerFilterControllerEventHandlerPlugin(): LogoutInvalidatedCustomerFilterControllerEventHandlerPlugin
    {
        return new LogoutInvalidatedCustomerFilterControllerEventHandlerPlugin();
    }

    /**
     * @return array<int, mixed>
     */
    public function createMockController(): array
    {
        return [
            new MockController(),
            'mockAction',
        ];
    }

    /**
     * @param callable $controller
     *
     * @return \Symfony\Component\HttpKernel\Event\ControllerEvent
     */
    public function createControllerEvent(callable $controller): ControllerEvent
    {
        $controllerEvent = new ControllerEvent(
            $this->getHttpKernelMock(),
            $controller,
            Request::createFromGlobals(),
            HttpKernelInterface::MAIN_REQUEST,
        );

        return $controllerEvent;
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getHttpKernelMock(): HttpKernelInterface
    {
        /** @var \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernelMock */
        $httpKernelMock = Stub::makeEmpty(HttpKernelInterface::class);

        return $httpKernelMock;
    }
}
