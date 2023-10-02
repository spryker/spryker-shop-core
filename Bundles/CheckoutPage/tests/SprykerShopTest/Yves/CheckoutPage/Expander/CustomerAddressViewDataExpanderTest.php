<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CheckoutPage\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Expander\AddressViewDataExpanderInterface;
use SprykerShop\Yves\CheckoutPage\Expander\CustomerAddressViewDataExpander;
use SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester;

/**
 * @group SprykerShop
 * @group Yves
 * @group CheckoutPage
 * @group Expander
 * @group CustomerAddressViewDataExpanderTest
 */
class CustomerAddressViewDataExpanderTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Expander\CustomerAddressViewDataExpander::PARAMETER_HAS_CUSTOMER_ADDRESSES
     *
     * @var string
     */
    protected const PARAMETER_HAS_CUSTOMER_ADDRESSES = 'hasCustomerAddresses';

    /**
     * @var \SprykerShopTest\Yves\CheckoutPage\CheckoutPageTester
     */
    protected CheckoutPageTester $tester;

    /**
     * @dataProvider getExpandDataProvider
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param bool $expectedHasCustomerAddresses
     *
     * @return void
     */
    public function testExpand(?CustomerTransfer $customerTransfer, bool $expectedHasCustomerAddresses): void
    {
        // Act
        $viewData = $this->createCustomerAddressViewDataExpander($customerTransfer)->expand([]);

        // Assert
        $this->assertSame($expectedHasCustomerAddresses, $viewData[static::PARAMETER_HAS_CUSTOMER_ADDRESSES]);
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\CustomerTransfer|bool>>
     */
    protected function getExpandDataProvider(): array
    {
        return [
            'Should expand view data with hasCustomerAddresses equals to false when customer is empty.' => [
                null, false,
            ],
            'Should expand view data with hasCustomerAddresses equals to false when customer does not have addresses collection.' => [
                new CustomerTransfer(), false,
            ],
            'Should expand view data with hasCustomerAddresses equals to false when customer has empty addresses collection.' => [
                (new CustomerTransfer())->setAddresses(new AddressesTransfer()),
                false,
            ],
            'Should expand view data with hasCustomerAddresses equals to true when customer has addresses.' => [
                (new CustomerTransfer())->setAddresses(
                    (new AddressesTransfer())->addAddress((new AddressTransfer())->setIdCustomerAddress(1)),
                ),
                true,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCustomerClientMock(?CustomerTransfer $customerTransfer = null): CheckoutPageToCustomerClientInterface
    {
        $customerClientMock = $this->getMockBuilder(CheckoutPageToCustomerClientInterface::class)
            ->getMock();

        $customerClientMock->method('getCustomer')->willReturn($customerTransfer);

        return $customerClientMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \SprykerShop\Yves\CheckoutPage\Expander\AddressViewDataExpanderInterface
     */
    protected function createCustomerAddressViewDataExpander(?CustomerTransfer $customerTransfer = null): AddressViewDataExpanderInterface
    {
        return new CustomerAddressViewDataExpander(
            $this->createCustomerClientMock($customerTransfer),
        );
    }
}
