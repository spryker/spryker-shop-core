<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\DateTimeConfiguratorPageExample\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleBusinessFactory;
use SprykerShop\Zed\DateTimeConfiguratorPageExample\Dependency\Facade\DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DateTimeConfiguratorPageExample
 * @group Business
 * @group Facade
 * @group DateTimeConfiguratorPageExampleFacadeTest
 * Add your own group annotations below this line
 */
class DateTimeConfiguratorPageExampleFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const DEMO_DATE_TIME_CONFIGURATOR_TEST_STORE = 'AT';
    /**
     * @var string
     */
    protected const DATE_TIME_CONFIGURATOR_KEY = 'DATE_TIME_CONFIGURATOR';
    /**
     * @var string
     */
    protected const TEST_SKU = 'demo_date_time_configurator_test_sku';
    /**
     * @var int
     */
    protected const TEST_STORE_AVAILABILITY = 5;
    /**
     * @var int
     */
    protected const TEST_PRODUCT_CONCRETE_AVAILABILITY = 10;
    /**
     * @var int
     */
    protected const TEST_PRODUCT_CONFIGURATION_INSTANCE_AVAILABILITY = 15;
    /**
     * @var string
     */
    protected const TEST_PRICE_NET_MODE = 'NET_MODE';
    /**
     * @var string
     */
    protected const PRICE_GROSS_MODE = 'GROSS_MODE';
    /**
     * @var int
     */
    protected const TEST_PRICE = 100000;

    /**
     * @var \SprykerShop\Zed\DateTimeConfiguratorPageExample\Dependency\Facade\DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $availabilityFacadeMock;

    /**
     * @var \SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleFacadeInterface
     */
    protected $dateTimeConfiguratorPageExampleFacade;

    /**
     * @var \SprykerTest\Zed\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->availabilityFacadeMock = $this->getMockBuilder(
            DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface::class
        )->onlyMethods(['findOrCreateProductConcreteAvailabilityBySkuForStore'])->getMockForAbstractClass();

        $dateTimeConfiguratorPageExampleFactoryMock = $this->getMockBuilder(
            DateTimeConfiguratorPageExampleBusinessFactory::class
        )->disableOriginalConstructor()->setMethodsExcept([
            'createProductConcreteAvailabilityReader',
        ])->getMock();

        $dateTimeConfiguratorPageExampleFactoryMock->method('getAvailabilityFacade')
            ->willReturn($this->availabilityFacadeMock);

        $this->dateTimeConfiguratorPageExampleFacade = $this->tester->getFacade()
            ->setFactory($dateTimeConfiguratorPageExampleFactoryMock);
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityWillReturnNullWhenAvailabilityNotSet(): void
    {
        //Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductConfigurationInstance((new ProductConfigurationInstanceTransfer()));

        $this->availabilityFacadeMock->expects($this->once())
            ->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn(null);

        //Act
        $productConcreteAvailabilityTransfer = $this->dateTimeConfiguratorPageExampleFacade
            ->findProductConcreteAvailability(
                static::TEST_SKU,
                new StoreTransfer(),
                $productAvailabilityCriteriaTransfer
            );

        //Assert
        $this->assertNull($productConcreteAvailabilityTransfer);
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityWillReturnProductConcreteAvailabilityWhenConfiguratorAvailabilityNotSet(): void
    {
        //Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductConfigurationInstance((new ProductConfigurationInstanceTransfer()));

        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setAvailability(
                (new Decimal(static::TEST_PRODUCT_CONCRETE_AVAILABILITY))
            );

        $this->availabilityFacadeMock->expects($this->once())
            ->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn($productConcreteAvailabilityTransfer);

        //Act
        $productConcreteAvailabilityTransfer = $this->dateTimeConfiguratorPageExampleFacade
            ->findProductConcreteAvailability(
                static::TEST_SKU,
                new StoreTransfer(),
                $productAvailabilityCriteriaTransfer
            );

        //Assert
        $this->assertSame(
            static::TEST_PRODUCT_CONCRETE_AVAILABILITY,
            $productConcreteAvailabilityTransfer->getAvailability()->toInt()
        );
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityWillReturnConfiguratorAvailabilityWhenConfiguratorAvailabilitySetAndNoConcreteAvailability(): void
    {
        //Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())
                    ->setAvailableQuantity(static::TEST_PRODUCT_CONFIGURATION_INSTANCE_AVAILABILITY)
            );

        $this->availabilityFacadeMock->expects($this->once())
            ->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn(null);

        //Act
        $productConcreteAvailabilityTransfer = $this->dateTimeConfiguratorPageExampleFacade
            ->findProductConcreteAvailability(
                static::TEST_SKU,
                new StoreTransfer(),
                $productAvailabilityCriteriaTransfer
            );

        //Assert
        $this->assertSame(
            static::TEST_PRODUCT_CONFIGURATION_INSTANCE_AVAILABILITY,
            $productConcreteAvailabilityTransfer->getAvailability()->toInt()
        );
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityWillIgnoreConcreteAvailabilityWhenBothAvailabilitySet(): void
    {
        //Arrange
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())
                    ->setAvailableQuantity(static::TEST_PRODUCT_CONFIGURATION_INSTANCE_AVAILABILITY)
            );

        $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
            ->setAvailability(
                (new Decimal(static::TEST_PRODUCT_CONCRETE_AVAILABILITY))
            );

        $this->availabilityFacadeMock->expects($this->once())
            ->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn($productConcreteAvailabilityTransfer);

        //Act
        $productConcreteAvailabilityTransfer = $this->dateTimeConfiguratorPageExampleFacade
            ->findProductConcreteAvailability(
                static::TEST_SKU,
                new StoreTransfer(),
                $productAvailabilityCriteriaTransfer
            );

        //Assert
        $this->assertSame(
            static::TEST_PRODUCT_CONFIGURATION_INSTANCE_AVAILABILITY,
            $productConcreteAvailabilityTransfer->getAvailability()->toInt()
        );
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityWillReturnNullWhenProductAvailabilityCriteriaNull(): void
    {
        //Arrange
        $storeTransfer = (new StoreTransfer())->setName('TEST_STORE');

        //Act
        $productConcreteAvailabilityTransfer = $this->dateTimeConfiguratorPageExampleFacade
            ->findProductConcreteAvailability(static::TEST_SKU, $storeTransfer);

        //Assert
        $this->assertNull($productConcreteAvailabilityTransfer);
    }
}
