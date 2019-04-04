<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentProductWidget\Twig\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentProductWidget\ContentProductWidgetDependencyProvider;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Plugin\ContentProductAbstractListTwigPlugin;
use Twig\Environment;
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

    protected const DEFAULT_TEMPLATE = 'default';
    protected const WRONG_TEMPLATE = 'wrong';

    protected const CONTENT_ID = 1;
    protected const CONTENT_TERM = 'TERM';

    protected const RENDERED_STRING = 'output';

    /**
     * @var \SprykerShop\Yves\ContentProductWidget\Plugin\ContentProductAbstractListTwigPlugin
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
     * @var \SprykerShopTest\Yves\ContentProductWidget\ContentProductAbstractWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Store::getInstance()->setCurrentLocale(static::STORE);
    }

    /**
     * @return void
     */
    public function testContentProductAbstractNotFound(): void
    {
        $contentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();
        $productAbstractContent = call_user_func($contentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);

        $this->assertEquals(
            '<!--' . sprintf(ContentProductAbstractListTwigPlugin::CONTENT_NOT_FOUND_MESSAGE_TEMPLATE, static::CONTENT_ID) . '-->',
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

        $contentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();
        $productAbstractContent = call_user_func($contentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);

        $this->assertEquals(
            '<!--' . sprintf(
                ContentProductAbstractListTwigPlugin::CONTENT_WRONG_TYPE_TEMPLATE,
                ContentProductAbstractListTwigPlugin::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT,
                static::CONTENT_ID
            ) . '-->',
            $productAbstractContent
        );
    }

    /**
     * @return void
     */
    public function testContentProductAbstractWrongTemplate(): void
    {
        $contentTypeContextTransfer = new ContentProductAbstractListTypeTransfer();
        $contentTypeContextTransfer->setIdProductAbstracts([static::CONTENT_ID]);

        $this->setContentProductClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();

        $contentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();

        $productAbstractContent = call_user_func($contentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, static::WRONG_TEMPLATE);

        $this->assertEquals(
            '<!--' . sprintf(
                ContentProductAbstractListTwigPlugin::CONTENT_NOT_SUPPORTED_MESSAGE_TEMPLATE,
                static::WRONG_TEMPLATE
            ) . '-->',
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

        $contentProductAbstractListTwigPlugin = $this->getContentProductAbstractListTwigPlugin();

        $productAbstractContent = call_user_func($contentProductAbstractListTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);

        $this->assertEquals($productAbstractContent, static::RENDERED_STRING);
    }

    /**
     * @return void
     */
    protected function setContentProductClientException(): void
    {
        $contentProductWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductWidgetToContentProductClientBridgeInterface::class)->getMock();
        $contentProductWidgetToContentProductClientBridge->method('findContentProductAbstractListType')->willThrowException(new InvalidProductAbstractListTypeException());
        $this->tester->setDependency(ContentProductWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT, $contentProductWidgetToContentProductClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentProductClientReturn(?ContentProductAbstractListTypeTransfer $contentTypeContextTransfer = null): void
    {
        $contentProductWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductWidgetToContentProductClientBridgeInterface::class)->getMock();
        $contentProductWidgetToContentProductClientBridge->method('findContentProductAbstractListType')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentProductWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT, $contentProductWidgetToContentProductClientBridge);
    }

    /**
     * @return void
     */
    protected function setProductStorageClientReturn(): void
    {
        $contentProductWidgetToProductStorageClientBridge = $this->getMockBuilder(ContentProductWidgetToProductStorageClientBridgeInterface::class)->getMock();
        $contentProductWidgetToProductStorageClientBridge->method('getProductAbstractCollection')->willReturn([[]]);
        $contentProductWidgetToProductStorageClientBridge->method('mapProductAbstractStorageData')->willReturn(new ProductViewTransfer());
        $this->tester->setDependency(ContentProductWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE, $contentProductWidgetToProductStorageClientBridge);
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentProductAbstractListTwigPlugin()
    {
        return $this->getTwig()->getFunction(ContentProductAbstractListTwigPlugin::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT);
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Plugin\ContentProductAbstractListTwigPlugin
     */
    protected function createTwigPlugin(): ContentProductAbstractListTwigPlugin
    {
        return new ContentProductAbstractListTwigPlugin();
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwig(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)->setConstructorArgs([new FilesystemLoader()])
            ->setMethods(['render'])
            ->getMock();

        $twigMock->method('render')->willReturn(static::RENDERED_STRING);
        $twigPlugin = $this->createTwigPlugin();
        $twigPlugin->extend($twigMock, $this->getMockBuilder(ContainerInterface::class)->getMock());

        return $twigMock;
    }
}
