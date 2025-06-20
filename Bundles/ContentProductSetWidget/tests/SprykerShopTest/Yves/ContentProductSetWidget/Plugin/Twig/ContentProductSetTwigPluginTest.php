<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentProductSetWidget\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentProductSetTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentProductSet\Exception\InvalidProductSetTermException;
use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ContentProductSetWidget\ContentProductSetWidgetDependencyProvider;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Plugin\Twig\ContentProductSetTwigPlugin;
use SprykerShop\Yves\ContentProductSetWidget\Twig\ContentProductSetTwigFunctionProvider;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentProductSetWidget
 * @group Plugin
 * @group Twig
 * @group ContentProductSetTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentProductSetTwigPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE = 'de_DE';

    /**
     * @var string
     */
    protected const DEFAULT_TEMPLATE = 'default';

    /**
     * @var string
     */
    protected const WRONG_TEMPLATE = 'wrong';

    /**
     * @var int
     */
    protected const CONTENT_ID = 0;

    /**
     * @var string
     */
    protected const CONTENT_KEY = 'test-key';

    /**
     * @var string
     */
    protected const CONTENT_TERM = 'TERM';

    /**
     * @var string
     */
    protected const MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND = '<strong>Content product set with content key "test-key" not found.</strong>';

    /**
     * @var string
     */
    protected const MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE = '<strong>Content product set widget could not be rendered because the content item with key "test-key" is not a product set.</strong>';

    /**
     * @var string
     */
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"wrong" is not supported name of template.</strong>';

    /**
     * @var string
     */
    protected const RENDERED_STRING = 'output';

    /**
     * @var array
     */
    protected const PARAM_ATTRIBUTE_VALUE = [];

    /**
     * @var \SprykerShopTest\Yves\ContentProductSetWidget\ContentProductSetWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContentProductSetNotFound(): void
    {
        $this->markTestSkipped('Test needs to be fixed.');

        // Act
        $productAbstractContent = call_user_func(
            $this->getContentProductSetTwigPlugin()->getCallable(),
            $this->getContext(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND, $productAbstractContent);
    }

    /**
     * @return void
     */
    public function testContentProductSetWrongType(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setIdContent(static::CONTENT_ID);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);
        $this->setContentProductClientException();

        // Act
        $productSetContent = call_user_func(
            $this->getContentProductSetTwigPlugin()->getCallable(),
            $this->getContext(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE, $productSetContent);
    }

    /**
     * @return void
     */
    public function testContentProductSetWrongTemplate(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentProductSetTypeTransfer();
        $contentTypeContextTransfer->setIdProductSet(static::CONTENT_ID);
        $this->setContentProductSetClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();

        // Act
        $productSetContent = call_user_func(
            $this->getContentProductSetTwigPlugin()->getCallable(),
            $this->getContext(),
            static::CONTENT_KEY,
            static::WRONG_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $productSetContent);
    }

    /**
     * @return void
     */
    public function testContentProductSetRendering(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentProductSetTypeTransfer();
        $contentTypeContextTransfer->setIdProductSet(static::CONTENT_ID);

        $this->setContentProductSetClientReturn($contentTypeContextTransfer);
        $this->setProductStorageClientReturn();
        $this->setProductSetStorageClientReturn();

        // Act
        $productSetContent = call_user_func(
            $this->getContentProductSetTwigPlugin()->getCallable(),
            $this->getContext(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::RENDERED_STRING, $productSetContent);
    }

    /**
     * @return void
     */
    protected function setContentProductClientException(): void
    {
        $contentProductSetWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductSetWidgetToContentProductSetClientInterface::class)->getMock();
        $contentProductSetWidgetToContentProductClientBridge->method('executeProductSetTypeByKey')->willThrowException(new InvalidProductSetTermException());
        $this->tester->setDependency(ContentProductSetWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT_SET, $contentProductSetWidgetToContentProductClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentProductSetClientReturn(?ContentProductSetTypeTransfer $contentTypeContextTransfer = null): void
    {
        $contentProductSetWidgetToContentProductClientBridge = $this->getMockBuilder(ContentProductSetWidgetToContentProductSetClientInterface::class)->getMock();
        $contentProductSetWidgetToContentProductClientBridge->method('executeProductSetTypeByKey')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentProductSetWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT_SET, $contentProductSetWidgetToContentProductClientBridge);
    }

    /**
     * @return void
     */
    protected function setProductSetStorageClientReturn(): void
    {
        $contentProductSetWidgetToProductSetStorageClientBridge = $this->getMockBuilder(ContentProductSetWidgetToProductSetStorageClientInterface::class)->getMock();
        $contentProductSetWidgetToProductSetStorageClientBridge->method('getProductSetByIdProductSet')->willReturn(new ProductSetDataStorageTransfer());
        $this->tester->setDependency(ContentProductSetWidgetDependencyProvider::CLIENT_PRODUCT_SET_STORAGE, $contentProductSetWidgetToProductSetStorageClientBridge);
    }

    /**
     * @return void
     */
    protected function setProductStorageClientReturn(): void
    {
        $contentProductSetWidgetToProductStorageClientBridge = $this->getMockBuilder(ContentProductSetWidgetToProductStorageClientInterface::class)->getMock();
        $contentProductSetWidgetToProductStorageClientBridge->method('findProductAbstractStorageData')->willReturn([]);
        $contentProductSetWidgetToProductStorageClientBridge->method('mapProductAbstractStorageData')->willReturn(new ProductViewTransfer());
        $this->tester->setDependency(ContentProductSetWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE, $contentProductSetWidgetToProductStorageClientBridge);
    }

    /**
     * @return \Twig\TwigFunction|bool
     */
    protected function getContentProductSetTwigPlugin()
    {
        $functionName = new ReflectionClassConstant(ContentProductSetTwigFunctionProvider::class, 'FUNCTION_CONTENT_PRODUCT_SET');

        return $this->createTwigMock()->getFunction($functionName->getValue());
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Plugin\Twig\ContentProductSetTwigPlugin
     */
    protected function createTwigPlugin(): ContentProductSetTwigPlugin
    {
        return new ContentProductSetTwigPlugin();
    }

    /**
     * @return \Twig\Environment|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)->setConstructorArgs([new FilesystemLoader()])
            ->onlyMethods(['render'])
            ->getMock();

        $twigMock->method('render')->willReturn(static::RENDERED_STRING);
        $twigPlugin = $this->createTwigPlugin();
        $twigPlugin->extend($twigMock, $this->getMockBuilder(ContainerInterface::class)->getMock());

        return $twigMock;
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        $context = [];
        $context['app']['request'] = $this->createRequestMock();

        return $context;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRequestMock(): Request
    {
        $query = $this->getMockBuilder(ParameterBag::class)->getMock();
        $query->method('get')
            ->willReturn(static::PARAM_ATTRIBUTE_VALUE);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->query = $query;

        return $request;
    }
}
