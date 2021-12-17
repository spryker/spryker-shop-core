<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ReflectionClass;
use SprykerShop\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider;

/**
 * @group CheckoutAddressFormDataProviderTest
 */
class CheckoutAddressFormDataProviderTest extends Unit
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider
     */
    protected $dataProvider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dataProvider = $this->getMockBuilder(CheckoutAddressFormDataProvider::class)->disableOriginalConstructor()->getMock();
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
     * @param array $parameters Array of parameters to pass into method.
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
