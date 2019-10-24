<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopCmsSlot\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientBridge;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientInterface;
use SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin;
use SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotDependencyProvider;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

class ShopCmsSlotTwigPluginTest extends Unit
{
    protected const CONTENT = 'test content';
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
        $cmsSlotContentResponseTransfer = $this->tester->getCmsSlotContentResponseTransfer([
            CmsSlotContentResponseTransfer::CONTENT => static::CONTENT,
        ]);

        $this->setCmsSlotContentPluginDependency($cmsSlotContentResponseTransfer);
        $this->setCmsSlotStorageClientDependency(new CmsSlotTransfer());

        $cmsSlotContextTransfer = $this->tester->getCmsSlotContextTransfer(
            static::SLOT_KEY,
            static::PROVIDED_DATA,
            static::REQUIRED_DATA,
            static::AUTO_FILLED_DATA
        );
        $shopCmsSlotContent = (new ShopCmsSlotTwigPlugin())
            ->getSlotContent($cmsSlotContextTransfer);

        $this->assertEquals(static::CONTENT, $shopCmsSlotContent);
    }

    /**
     * @return void
     */
    public function testShopCmsSlotTwigPluginReturnsEmptyStringOnMissingRequiredData(): void
    {
        $cmsSlotContentResponseTransfer = $this->tester->getCmsSlotContentResponseTransfer([
            CmsSlotContentResponseTransfer::CONTENT => static::CONTENT,
        ]);

        $this->setCmsSlotContentPluginDependency($cmsSlotContentResponseTransfer);
        $this->setCmsSlotStorageClientDependency(new CmsSlotTransfer());

        $cmsSlotContextTransfer = $this->tester->getCmsSlotContextTransfer(
            static::SLOT_KEY,
            static::PROVIDED_DATA,
            ['missing-provided-key'] + static::REQUIRED_DATA,
            static::AUTO_FILLED_DATA
        );
        $shopCmsSlotContent = (new ShopCmsSlotTwigPlugin())
            ->getSlotContent($cmsSlotContextTransfer);

        $this->assertEquals('', $shopCmsSlotContent);
    }

    /**
     * @return void
     */
    public function testShopCmsSlotTwigPluginReturnsEmptyStringIfSlotKeyIsWrongOrSlotIsInactive(): void
    {
        $cmsSlotContentResponseTransfer = $this->tester->getCmsSlotContentResponseTransfer([
            CmsSlotContentResponseTransfer::CONTENT => static::CONTENT,
        ]);

        $this->setCmsSlotContentPluginDependency($cmsSlotContentResponseTransfer);
        $this->setCmsSlotStorageClientDependency(null);

        $cmsSlotContextTransfer = $this->tester->getCmsSlotContextTransfer(
            static::SLOT_KEY,
            static::PROVIDED_DATA,
            static::REQUIRED_DATA,
            static::AUTO_FILLED_DATA
        );
        $shopCmsSlotContent = (new ShopCmsSlotTwigPlugin())
            ->getSlotContent($cmsSlotContextTransfer);

        $this->assertEquals('', $shopCmsSlotContent);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentResponseTransfer $cmsSlotContentResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface
     */
    protected function getCmsSlotContentPluginMock(CmsSlotContentResponseTransfer $cmsSlotContentResponseTransfer): CmsSlotContentPluginInterface
    {
        $cmsSlotContentPluginMock = $this->getMockBuilder(CmsSlotContentPluginInterface::class)->getMock();
        $cmsSlotContentPluginMock->method('getSlotContent')->willReturn($cmsSlotContentResponseTransfer);

        return $cmsSlotContentPluginMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer|null $cmsSlotTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientInterface
     */
    protected function getCmsSlotStorageClientMock(?CmsSlotTransfer $cmsSlotTransfer): ShopCmsSlotToCmsSlotStorageClientInterface
    {
        $cmsSlotStorageClientMock = $this->getMockBuilder(ShopCmsSlotToCmsSlotStorageClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $cmsSlotStorageClientMock->method('findCmsSlotByKey')->willReturn($cmsSlotTransfer);

        return $cmsSlotStorageClientMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentResponseTransfer $cmsSlotContentResponseTransfer
     *
     * @return void
     */
    protected function setCmsSlotContentPluginDependency(CmsSlotContentResponseTransfer $cmsSlotContentResponseTransfer): void
    {
        $this->tester->setDependency(
            ShopCmsSlotDependencyProvider::PLUGIN_CMS_SLOT_CONTENT,
            $this->getCmsSlotContentPluginMock($cmsSlotContentResponseTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer|null $cmsSlotTransfer
     *
     * @return void
     */
    protected function setCmsSlotStorageClientDependency(?CmsSlotTransfer $cmsSlotTransfer): void
    {
        $this->tester->setDependency(
            ShopCmsSlotDependencyProvider::CLIENT_CMS_SLOT_STORAGE,
            $this->getCmsSlotStorageClientMock($cmsSlotTransfer)
        );
    }
}
