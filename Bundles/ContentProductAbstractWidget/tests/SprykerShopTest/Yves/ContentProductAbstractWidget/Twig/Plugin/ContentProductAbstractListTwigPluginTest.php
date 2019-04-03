<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentProductAbstractWidget\Twig\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentProductAbstractWidget\ContentProductAbstractWidgetDependencyProvider;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductAbstractWidget\Plugin\ContentProductAbstractListTwigPlugin;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 * @group SprykerShop
 * @group Yves
 * @group Client
 * @group Plugin
 * @group ContentProductAbstractListTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentProductAbstractListTwigPluginTest extends Unit
{
    protected const STORE = 'de_DE';

    protected const CONTENT_ID = 1;
    protected const CONTENT_TERM = 'TERM';

    /**
     * @var \SprykerShop\Yves\ContentProductAbstractWidget\Plugin\ContentProductAbstractListTwigPlugin
     */
    protected $twigPlugin;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \SprykerShopTest\Yves\ContentProductAbstractWidget\ContentProductAbstractWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Store::getInstance()->setCurrentLocale(static::STORE);

        $this->initialiseTwigServiceProviderPlugin();
    }

    /**
     * @return void
     */
    public function testContentProductAbstractNotFound(): void
    {
        $ContentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();
        $productAbstractContent = call_user_func($ContentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, null);

        $this->assertEquals(
            sprintf(ContentProductAbstractListTwigPlugin::CONTENT_NOT_FOUND_MESSAGE_TEMPLATE, static::CONTENT_ID),
            $productAbstractContent
        );
    }

    /**
     * @return void
     */
    public function testContentProductAbstractWrongType(): void
    {
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setIdContent(static::CONTENT_ID);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);
        $this->setContentProductClientException();

        $ContentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();
        $productAbstractContent = call_user_func($ContentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, null);

        $this->assertEquals(
            sprintf(ContentProductAbstractListTwigPlugin::CONTENT_WRONG_TYPE_TEMPLATE, ContentProductAbstractListTwigPlugin::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT, static::CONTENT_ID),
            $productAbstractContent
        );
    }

    /**
     * @return void
     */
    public function testContentProductAbstractRendering(): void
    {
        $contentTypeContextTransfer = new ContentProductAbstractListTypeTransfer();
        $contentTypeContextTransfer->setIdProductAbstracts([static::CONTENT_ID]);

        $this->setContentProductClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();

        $ContentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();
        $this->expectException(LoaderError::class);
        $productAbstractContent = call_user_func($ContentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, null);
    }

    /**
     * @return void
     */
    protected function setContentProductClientException(): void
    {
        $contentProductAbstractWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductAbstractWidgetToContentProductClientBridgeInterface::class)->getMock();
        $contentProductAbstractWidgetToContentProductClientBridge->method('findContentProductAbstractListType')->willThrowException(new InvalidProductAbstractListTypeException());
        $this->tester->setDependency(ContentProductAbstractWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT, $contentProductAbstractWidgetToContentProductClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentProductClientReturn(?ContentProductAbstractListTypeTransfer $contentTypeContextTransfer = null): void
    {
        $contentProductAbstractWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductAbstractWidgetToContentProductClientBridgeInterface::class)->getMock();
        $contentProductAbstractWidgetToContentProductClientBridge->method('findContentProductAbstractListType')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentProductAbstractWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT, $contentProductAbstractWidgetToContentProductClientBridge);
    }

    /**
     * @return void
     */
    protected function setProductStorageClientReturn(): void
    {
        $contentProductAbstractWidgetToProductStorageClientBridge = $this->getMockBuilder(ContentProductAbstractWidgetToProductStorageClientBridgeInterface::class)->getMock();
        $contentProductAbstractWidgetToProductStorageClientBridge->method('getProductAbstractCollection')->willReturn([[]]);
        $contentProductAbstractWidgetToProductStorageClientBridge->method('mapProductAbstractStorageData')->willReturn(new ProductViewTransfer());
        $this->tester->setDependency(ContentProductAbstractWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE, $contentProductAbstractWidgetToProductStorageClientBridge);
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentProductAbstractListTwigPlugin()
    {
        return $this->getTwig()->getFunction(ContentProductAbstractListTwigPlugin::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT);
    }

    /**
     * @return void
     */
    protected function initialiseTwigServiceProviderPlugin(): void
    {
        $twigPlugin = $this->createTwigPlugin();
        $twig = $this->getTwig();
        $twigPlugin->extend($twig, $this->getMockBuilder(ContainerInterface::class)->getMock());
    }

    /**
     * @return \SprykerShop\Yves\ContentProductAbstractWidget\Plugin\ContentProductAbstractListTwigPlugin
     */
    protected function createTwigPlugin(): ContentProductAbstractListTwigPlugin
    {
        return new ContentProductAbstractListTwigPlugin();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getTwig(): Environment
    {
        if (!$this->twig) {
            $this->twig = new Environment(new FilesystemLoader());
        }

        return $this->twig;
    }
}
