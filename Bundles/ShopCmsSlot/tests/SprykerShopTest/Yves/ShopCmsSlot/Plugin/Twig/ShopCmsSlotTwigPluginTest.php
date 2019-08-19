<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopApplication\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotDataTransfer;
use SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin;
use SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotDependencyProvider;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface;

class ShopCmsSlotTwigPluginTest extends Unit
{
    protected const FRAGMENT_DATA = 'test fragment data';
    protected const SLOT_KEY = 'test-slot-key';
    protected const PROVIDED_DATA = [
        'provided-key' => 'data-value',
    ];
    protected const AUTO_FILLED_DATA = [];
    protected const REQUIRED_DATA = [
        'provided-key',
    ];

    /**
     * @var \SprykerShopTest\Yves\ShopCmsSlot\ShopCmsSlotYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShopCmsSlotTwigPluginReturnsExpectedStringOnValidData(): void
    {
        $cmsSlotDataTransfer = $this->tester->getCmsSlotDataTransfer([
            CmsSlotDataTransfer::FRAGMENT_DATA => static::FRAGMENT_DATA,
        ]);

        $cmsSlotPluginMock = $this->getCmsSlotPluginMock();
        $cmsSlotPluginMock->method('getSlotContent')->willReturn($cmsSlotDataTransfer);

        $this->tester->setDependency(
            ShopCmsSlotDependencyProvider::PLUGIN_SHOP_CMS_SLOT_HANDLER,
            $cmsSlotPluginMock
        );

        $shopCmsSlotContent = (new ShopCmsSlotTwigPlugin())
            ->getSlotContent(
                static::SLOT_KEY,
                static::PROVIDED_DATA,
                static::REQUIRED_DATA,
                static::AUTO_FILLED_DATA
            );

        $this->assertEquals(static::FRAGMENT_DATA, $shopCmsSlotContent);
    }

    /**
     * @return void
     */
    public function testShopCmsSlotTwigPluginReturnsEmptyStringOnMissingRequiredData(): void
    {
        $cmsSlotDataTransfer = $this->tester->getCmsSlotDataTransfer([
            CmsSlotDataTransfer::FRAGMENT_DATA => static::FRAGMENT_DATA,
        ]);

        $cmsSlotPluginMock = $this->getCmsSlotPluginMock();
        $cmsSlotPluginMock->method('getSlotContent')->willReturn($cmsSlotDataTransfer);

        $this->tester->setDependency(
            ShopCmsSlotDependencyProvider::PLUGIN_SHOP_CMS_SLOT_HANDLER,
            $cmsSlotPluginMock
        );

        $shopCmsSlotContent = (new ShopCmsSlotTwigPlugin())
            ->getSlotContent(
                static::SLOT_KEY,
                static::PROVIDED_DATA,
                ['missing-provided-key'] + static::REQUIRED_DATA,
                static::AUTO_FILLED_DATA
            );

        $this->assertEquals('', $shopCmsSlotContent);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface
     */
    protected function getCmsSlotPluginMock(): CmsSlotPluginInterface
    {
        return $this->getMockBuilder(CmsSlotPluginInterface::class)->getMock();
    }
}
