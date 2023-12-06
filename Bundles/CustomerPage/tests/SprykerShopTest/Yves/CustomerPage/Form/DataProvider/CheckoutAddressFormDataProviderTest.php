<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use ReflectionClass;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Form
 * @group DataProvider
 * @group CheckoutAddressFormDataProviderTest
 */
class CheckoutAddressFormDataProviderTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_CUSTOMER_ADDRESS = 888;

    /**
     * @var int
     */
    protected const ID_COMPANY_UNIT_ADDRESS = 999;

    /**
     * @see \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS
     *
     * @var int
     */
    protected const VALUE_ADD_NEW_ADDRESS = 0;

    /**
     * @var \SprykerShop\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider
     */
    protected CheckoutAddressFormDataProvider $dataProvider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dataProvider = $this->getMockBuilder(CheckoutAddressFormDataProvider::class)
            ->setMethods(['resolveShipmentForSingleAddressDelivery'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tests that given first and last name counts as billing address provided in quote.
     *
     * @return void
     */
    public function testIsBillingAddressInQuote(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setBillingAddress(new AddressTransfer());
        $result = $this->invokeMethod($this->dataProvider, 'isBillingAddressInQuote', [$quoteTransfer]);
        $this->assertFalse($result);

        $quoteTransfer->getBillingAddress()->setFirstName('Foo');
        $quoteTransfer->getBillingAddress()->setLastName('Bar');

        $result = $this->invokeMethod($this->dataProvider, 'isBillingAddressInQuote', [$quoteTransfer]);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider getSetItemLevelShippingAddressesWithPreviouslySelectedShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $previouslySelectedShipmentTransfer
     *
     * @return void
     */
    public function testSetItemLevelShippingAddressesWithPreviouslySelectedShipment(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $previouslySelectedShipmentTransfer
    ): void {
        // Arrange
        $this->dataProvider->method('resolveShipmentForSingleAddressDelivery')->willReturn($previouslySelectedShipmentTransfer);

        // Act
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->invokeMethod($this->dataProvider, 'setItemLevelShippingAddresses', [$quoteTransfer]);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();
        $this->assertSame($previouslySelectedShipmentTransfer, $itemTransfer->getShipment());
    }

    /**
     * @dataProvider getSetItemLevelShippingAddressesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $expectedIdCustomerAddress
     *
     * @return void
     */
    public function testSetItemLevelShippingAddresses(
        QuoteTransfer $quoteTransfer,
        ?int $expectedIdCustomerAddress
    ): void {
        // Arrange
        $this->dataProvider->method('resolveShipmentForSingleAddressDelivery')->willReturn(new ShipmentTransfer());

        // Act
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->invokeMethod($this->dataProvider, 'setItemLevelShippingAddresses', [$quoteTransfer]);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();
        $this->assertSame($expectedIdCustomerAddress, $itemTransfer->getShipmentOrFail()->getShippingAddressOrFail()->getIdCustomerAddress());
    }

    /**
     * @dataProvider getSetBundleItemLevelShippingAddressesWithPreviouslySelectedShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $previouslySelectedShipmentTransfer
     *
     * @return void
     */
    public function testSetBundleItemLevelShippingAddressesWithPreviouslySelectedShipment(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $previouslySelectedShipmentTransfer
    ): void {
        // Arrange
        $this->dataProvider->method('resolveShipmentForSingleAddressDelivery')->willReturn($previouslySelectedShipmentTransfer);

        // Act
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->invokeMethod($this->dataProvider, 'setBundleItemLevelShippingAddresses', [$quoteTransfer]);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getBundleItems()->getIterator()->current();
        $this->assertSame($previouslySelectedShipmentTransfer, $itemTransfer->getShipment());
    }

    /**
     * @dataProvider getSetBundleItemLevelShippingAddressesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $expectedIdCustomerAddress
     *
     * @return void
     */
    public function testSetBundleItemLevelShippingAddresses(
        QuoteTransfer $quoteTransfer,
        ?int $expectedIdCustomerAddress
    ): void {
        // Arrange
        $this->dataProvider->method('resolveShipmentForSingleAddressDelivery')->willReturn(new ShipmentTransfer());

        // Act
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->invokeMethod($this->dataProvider, 'setBundleItemLevelShippingAddresses', [$quoteTransfer]);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getBundleItems()->getIterator()->current();
        $this->assertSame($expectedIdCustomerAddress, $itemTransfer->getShipmentOrFail()->getShippingAddressOrFail()->getIdCustomerAddress());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteTransfer>>
     */
    protected function getSetItemLevelShippingAddressesWithPreviouslySelectedShipmentDataProvider(): array
    {
        return [
            'Should set previously selected shipment when item does not have shipment.' => [
                (new QuoteTransfer())->addItem(new ItemTransfer()), new ShipmentTransfer(),
            ],
            'Should set previously selected shipment when item does not have shipment address.' => [
                (new QuoteTransfer())->addItem((new ItemTransfer())->setShipment(new ShipmentTransfer())), new ShipmentTransfer(),
            ],
        ];
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\QuoteTransfer|int|null>>
     */
    protected function getSetItemLevelShippingAddressesDataProvider(): array
    {
        return [
            'Should not set `addNewAddress` value to address transfer when item has shipping address with customer address ID.' => [
                (new QuoteTransfer())->addItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress((new AddressTransfer())->setIdCustomerAddress(static::ID_CUSTOMER_ADDRESS)),
                )),
                static::ID_CUSTOMER_ADDRESS,
            ],
            'Should not set `addNewAddress` value to address transfer when item has shipping address with company unit address ID.' => [
                (new QuoteTransfer())->addItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress((new AddressTransfer())->setIdCompanyUnitAddress(static::ID_COMPANY_UNIT_ADDRESS)),
                )),
                null,
            ],
            'Should set `addNewAddress` value to address transfer when item has shipping address without customer address ID and company unit address ID.' => [
                (new QuoteTransfer())->addItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress(new AddressTransfer()),
                )),
                static::VALUE_ADD_NEW_ADDRESS,
            ],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteTransfer>>
     */
    protected function getSetBundleItemLevelShippingAddressesWithPreviouslySelectedShipmentDataProvider(): array
    {
        return [
            'Should set previously selected shipment when bundle item does not have shipment.' => [
                (new QuoteTransfer())->addBundleItem(new ItemTransfer()), new ShipmentTransfer(),
            ],
            'Should set previously selected shipment when bundle item does not have shipment address.' => [
                (new QuoteTransfer())->addBundleItem((new ItemTransfer())->setShipment(new ShipmentTransfer())), new ShipmentTransfer(),
            ],
        ];
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\QuoteTransfer|int|null>>
     */
    protected function getSetBundleItemLevelShippingAddressesDataProvider(): array
    {
        return [
            'Should not set `addNewAddress` value to address transfer when bundle item has shipping address with customer address ID.' => [
                (new QuoteTransfer())->addBundleItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress((new AddressTransfer())->setIdCustomerAddress(static::ID_CUSTOMER_ADDRESS)),
                )),
                static::ID_CUSTOMER_ADDRESS,
            ],
            'Should not set `addNewAddress` value to address transfer when bundle item has shipping address with company unit address ID.' => [
                (new QuoteTransfer())->addBundleItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress((new AddressTransfer())->setIdCompanyUnitAddress(static::ID_COMPANY_UNIT_ADDRESS)),
                )),
                null,
            ],
            'Should set `addNewAddress` value to address transfer when bundle item has shipping address without customer address ID and company unit address ID.' => [
                (new QuoteTransfer())->addBundleItem((new ItemTransfer())->setShipment(
                    (new ShipmentTransfer())->setShippingAddress(new AddressTransfer()),
                )),
                static::VALUE_ADD_NEW_ADDRESS,
            ],
        ];
    }

    /**
     * Call protected/private method of a class.
     *
     * So
     *   $this->invokeMethod($user, 'cryptPassword', array('passwordToCrypt'));
     * is equal to
     *   $user->cryptPassword('passwordToCrypt');
     * (assuming the method was directly publicly accessible
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call.
     * @param array<string, mixed> $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
