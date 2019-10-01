<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentProductWidget\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTermException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentProductWidget\ContentProductWidgetDependencyProvider;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Plugin\Twig\ContentProductAbstractListTwigPlugin;
use SprykerShop\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentProductWidget
 * @group Plugin
 * @group Twig
 * @group ContentProductAbstractListTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentProductAbstractListTwigPluginTest extends Unit
{
    protected const LOCALE = 'de_DE';

    protected const DEFAULT_TEMPLATE = 'top-title';
    protected const WRONG_TEMPLATE = 'wrong';

    protected const CONTENT_ID = 0;
    protected const CONTENT_KEY = 'test-key';
    protected const CONTENT_TERM = 'TERM';

    protected const MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND = '<strong>Content product abstract list with content key "test-key" not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE = '<strong>Content product abstract list widget could not be rendered because the content item with key "test-key" is not an abstract product list.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"wrong" is not supported name of template.</strong>';
    protected const RENDERED_STRING = 'output';

    /**
     * @var \SprykerShopTest\Yves\ContentProductWidget\ContentProductWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Store::getInstance()->setCurrentLocale(static::LOCALE);
    }

    /**
     * @return void
     */
    public function testContentProductAbstractNotFound(): void
    {
        // Act
        $productAbstractContent = call_user_func($this->getContentProductAbstractListTwigPlugin()->getCallable(), static::CONTENT_KEY, static::DEFAULT_TEMPLATE);

        // Assert
        $this->assertEquals(static::MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND, $productAbstractContent);
    }

    /**
     * @return void
     */
    public function testContentProductAbstractWrongType(): void
    {
        // Assign
        $this->setContentProductClientException();

        // Act
        $productAbstractContent = call_user_func($this->getContentProductAbstractListTwigPlugin()->getCallable(), static::CONTENT_KEY, static::DEFAULT_TEMPLATE);

        // Assert
        $this->assertEquals(static::MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE, $productAbstractContent);
    }

    /**
     * @return void
     */
    public function testContentProductAbstractWrongTemplate(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentProductAbstractListTypeTransfer();
        $contentTypeContextTransfer->setIdProductAbstracts([static::CONTENT_ID]);
        $this->setContentProductClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();

        // Act
        $productAbstractContent = call_user_func($this->getContentProductAbstractListTwigPlugin()->getCallable(), static::CONTENT_KEY, static::WRONG_TEMPLATE);

        // Assert
        $this->assertEquals(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $productAbstractContent);
    }

    /**
     * @return void
     */
    public function testContentProductAbstractRendering(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentProductAbstractListTypeTransfer();
        $contentTypeContextTransfer->setIdProductAbstracts([static::CONTENT_ID]);
        $this->setContentProductClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();

        // Act
        $productAbstractContent = call_user_func($this->getContentProductAbstractListTwigPlugin()->getCallable(), static::CONTENT_KEY, static::DEFAULT_TEMPLATE);

        // Assert
        $this->assertEquals(static::RENDERED_STRING, $productAbstractContent);
    }

    /**
     * @return void
     */
    protected function setContentProductClientException(): void
    {
        $contentProductWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductWidgetToContentProductClientBridgeInterface::class)->getMock();
        $contentProductWidgetToContentProductClientBridge->method('executeProductAbstractListTypeByKey')->willThrowException(new InvalidProductAbstractListTermException());
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
        $contentProductWidgetToContentProductClientBridge->method('executeProductAbstractListTypeByKey')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentProductWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT, $contentProductWidgetToContentProductClientBridge);
    }

    /**
     * @return void
     */
    protected function setProductStorageClientReturn(): void
    {
        $contentProductWidgetToProductStorageClientBridge = $this->getMockBuilder(ContentProductWidgetToProductStorageClientBridgeInterface::class)->getMock();
        $contentProductWidgetToProductStorageClientBridge->method('findProductAbstractStorageData')->willReturn([]);
        $contentProductWidgetToProductStorageClientBridge->method('mapProductAbstractStorageData')->willReturn(new ProductViewTransfer());
        $this->tester->setDependency(ContentProductWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE, $contentProductWidgetToProductStorageClientBridge);
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentProductAbstractListTwigPlugin()
    {
        $functionName = new ReflectionClassConstant(ContentProductAbstractListTwigFunction::class, 'FUNCTION_CONTENT_PRODUCT_ABSTRACT_LIST');

        return $this->getTwig()->getFunction($functionName->getValue());
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Plugin\Twig\ContentProductAbstractListTwigPlugin
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
