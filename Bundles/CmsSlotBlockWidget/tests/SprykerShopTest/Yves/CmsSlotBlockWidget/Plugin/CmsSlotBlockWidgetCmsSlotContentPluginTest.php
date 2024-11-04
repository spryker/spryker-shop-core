<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CmsSlotBlockWidget\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use SprykerShop\Yves\CmsSlotBlockWidget\Business\CmsSlotBlockWidgetDataProvider;
use SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetDependencyProvider;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Plugin\ShopCmsSlot\CmsSlotBlockWidgetCmsSlotContentPlugin;
use Twig\Environment;

class CmsSlotBlockWidgetCmsSlotContentPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SLOT_KEY = 'cms-slot-key';

    /**
     * @var string
     */
    protected const BLOCK_KEY = 'cms-block-key';

    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@Home/index/home.twig';

    /**
     * @var string
     */
    protected const CONTENT = 'cms block content';

    /**
     * @var \SprykerShopTest\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCmsSlotBlockWidgetTwigPluginReturnsExpectedStringOnValidData(): void
    {
        // Arrange
        $this->setCmsSlotBlockClientDependency();
        $this->setCmsSlotBlockStorageClientDependency(
            (new CmsSlotBlockCollectionTransfer())
                ->addCmsSlotBlock(
                    (new CmsSlotBlockTransfer())
                        ->setCmsBlockKey(static::BLOCK_KEY)
                        ->setConditions(new ArrayObject()),
                ),
        );
        $this->setCmsSlotBlockWidgetDataProviderMock(function ($twig, $context, $blockOptions) {
            $content = '';

            if (in_array(static::BLOCK_KEY, $blockOptions['keys'])) {
                $content .= static::CONTENT;
            }

            return $content;
        });
        $cmsSlotBlockWidgetCmsSlotContentPluginMock = $this->getCmsSlotBlockWidgetCmsSlotContentPluginMock();
        $cmsSlotContentRequestTransfer = $this->tester->getCmsSlotContentRequestTransfer([
            CmsSlotContentRequestTransfer::CMS_SLOT_KEY => static::SLOT_KEY,
            CmsSlotContentRequestTransfer::CMS_SLOT_TEMPLATE_PATH => static::TEMPLATE_PATH,
        ]);

        // Act
        $cmsSlotContentResponseTransfer = $cmsSlotBlockWidgetCmsSlotContentPluginMock->getSlotContent(
            $cmsSlotContentRequestTransfer,
        );

        // Assert
        $this->assertSame(static::CONTENT, $cmsSlotContentResponseTransfer->getContent());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CmsSlotBlockWidget\Plugin\ShopCmsSlot\CmsSlotBlockWidgetCmsSlotContentPlugin
     */
    protected function getCmsSlotBlockWidgetCmsSlotContentPluginMock(): CmsSlotBlockWidgetCmsSlotContentPlugin
    {
        $cmsSlotBlockWidgetCmsSlotContentPluginMock = $this
            ->getMockBuilder(CmsSlotBlockWidgetCmsSlotContentPlugin::class)
            ->onlyMethods(['getFactory'])
            ->getMock();

        $cmsSlotBlockWidgetFactory = $this->tester->getFactory();
        $cmsSlotBlockWidgetCmsSlotContentPluginMock->method('getFactory')
            ->willReturn($cmsSlotBlockWidgetFactory);

        return $cmsSlotBlockWidgetCmsSlotContentPluginMock;
    }

    /**
     * @param callable $twigFunctionCallable
     *
     * @return void
     */
    protected function setCmsSlotBlockWidgetDataProviderMock(callable $twigFunctionCallable): void
    {
        $cmsSlotBlockWidgetDataProviderMock = $this
            ->getMockBuilder(CmsSlotBlockWidgetDataProvider::class)
            ->onlyMethods(['getCmsBlockTwigFunction'])
            ->setConstructorArgs([
                $this->getMockBuilder(Environment::class)
                    ->getMock(),
                $this->tester->getFactory()->getCmsSlotBlockStorageClient(),
                $this->tester->getFactory()->getCmsSlotBlockClient(),
                $this->tester->getFactory()->getConfig(),
            ])
            ->getMock();
        $cmsSlotBlockWidgetDataProviderMock->method('getCmsBlockTwigFunction')->willReturn($twigFunctionCallable);
        $this->tester->mockFactoryMethod(
            'createCmsSlotBlockWidgetDataProvider',
            $cmsSlotBlockWidgetDataProviderMock,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
     */
    protected function getCmsSlotBlockStorageClientMock(
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface {
        $cmsSlotBlockStorageClientMock = $this->getMockBuilder(CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cmsSlotBlockStorageClientMock->method('getCmsSlotBlockCollection')->willReturn($cmsSlotBlockCollectionTransfer);

        return $cmsSlotBlockStorageClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface
     */
    protected function getCmsSlotBlockClientMock(): CmsSlotBlockWidgetToCmsSlotBlockClientInterface
    {
        $cmsSlotBlockClientMock = $this->getMockBuilder(CmsSlotBlockWidgetToCmsSlotBlockClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cmsSlotBlockClientMock->method('isCmsBlockVisibleInSlot')->willReturn(true);

        return $cmsSlotBlockClientMock;
    }

    /**
     * @return void
     */
    protected function setCmsSlotBlockClientDependency(): void
    {
        $this->tester->setDependency(
            CmsSlotBlockWidgetDependencyProvider::CLIENT_CMS_SLOT_BLOCK,
            $this->getCmsSlotBlockClientMock(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer|null $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    protected function setCmsSlotBlockStorageClientDependency(?CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $this->tester->setDependency(
            CmsSlotBlockWidgetDependencyProvider::CLIENT_CMS_SLOT_BLOCK_STORAGE,
            $this->getCmsSlotBlockStorageClientMock($cmsSlotBlockCollectionTransfer),
        );
    }
}
