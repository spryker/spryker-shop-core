<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentNavigationWidget\Twig\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\NavigationStorageTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentNavigation\ContentNavigationDependencyProvider;
use Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface;
use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetDependencyProvider;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Plugin\Twig\ContentNavigationTwigPlugin;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunctionProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentNavigationWidget
 * @group Plugin
 * @group Twig
 * @group ContentNavigationTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentNavigationTwigPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE = 'de_DE';

    /**
     * @var string
     */
    protected const DEFAULT_TEMPLATE = 'tree-inline';

    /**
     * @var string
     */
    protected const WRONG_TEMPLATE = 'wrong';

    /**
     * @var string
     */
    protected const CONTENT_KEY = '0';

    /**
     * @var string
     */
    protected const CONTENT_TERM = 'TERM';

    /**
     * @var string
     */
    protected const RENDERED_STRING = 'output';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_NOT_FOUND = '<b>Content Navigation with key 0 not found.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_WRONG_TYPE = '<b>Content Navigation could not be rendered because the content item with key 0 is not an navigation.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_WRONG_TEMPLATE = '<b>"wrong" is not supported name of template.</b>';

    /**
     * @var \SprykerShopTest\Yves\ContentNavigationWidget\ContentNavigationWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContentNavigationNotFound(): void
    {
        // Arrange
        $this->setContentNavigationToContentStorageClientReturn();

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_NAVIGATION_NOT_FOUND, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationWrongType(): void
    {
        // Arrange
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setKey(static::CONTENT_KEY);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);
        $this->setContentNavigationToContentStorageClientReturn($contentTypeContextTransfer);

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert

        $this->assertSame(static::MESSAGE_NAVIGATION_WRONG_TYPE, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationWrongTemplate(): void
    {
        // Arrange
        $contentNavigationTypeTransfer = new ContentNavigationTypeTransfer();
        $this->setContentNavigationWidgetToContentNavigationClientReturn($contentNavigationTypeTransfer);

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::WRONG_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_NAVIGATION_WRONG_TEMPLATE, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationWithInactiveNavigationWillReturnEmptyLine(): void
    {
        // Arrange
        $this->setContentNavigationWidgetToContentNavigationClientReturn(new ContentNavigationTypeTransfer());
        $this->setContentNavigationWidgetToNavigationStorageClientReturn(
            (new NavigationStorageTransfer())
                ->setIsActive(false)
                ->setKey(static::CONTENT_KEY),
        );

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame('', $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationRendering(): void
    {
        // Arrange
        $this->setContentNavigationWidgetToContentNavigationClientReturn(new ContentNavigationTypeTransfer());
        $this->setContentNavigationWidgetToNavigationStorageClientReturn(
            (new NavigationStorageTransfer())
                ->setIsActive(true)
                ->setKey(static::CONTENT_KEY),
        );

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::RENDERED_STRING, $navigationContent);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentNavigationTypeTransfer|null $contentNavigationTypeTransfer
     *
     * @return void
     */
    protected function setContentNavigationWidgetToContentNavigationClientReturn(?ContentNavigationTypeTransfer $contentNavigationTypeTransfer = null): void
    {
        $contentNavigationWidgetToContentNavigationClientBridge = $this
            ->getMockBuilder(ContentNavigationWidgetToContentNavigationClientInterface::class)->getMock();
        $contentNavigationWidgetToContentNavigationClientBridge
            ->method('executeNavigationTypeByKey')
            ->willReturn($contentNavigationTypeTransfer);
        $this->tester->setDependency(
            ContentNavigationWidgetDependencyProvider::CLIENT_CONTENT_NAVIGATION,
            $contentNavigationWidgetToContentNavigationClientBridge,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     *
     * @return void
     */
    protected function setContentNavigationWidgetToNavigationStorageClientReturn(NavigationStorageTransfer $navigationStorageTransfer): void
    {
        $contentNavigationWidgetToNavigationStorageClientBridge = $this
            ->getMockBuilder(ContentNavigationWidgetToNavigationStorageClientInterface::class)->getMock();
        $contentNavigationWidgetToNavigationStorageClientBridge
            ->method('findNavigationTreeByKey')
            ->willReturn($navigationStorageTransfer);
        $this->tester->setDependency(
            ContentNavigationWidgetDependencyProvider::CLIENT_NAVIGATION_STORAGE,
            $contentNavigationWidgetToNavigationStorageClientBridge,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentNavigationToContentStorageClientReturn(?ContentTypeContextTransfer $contentTypeContextTransfer = null): void
    {
        $contentNavigationWidgetToContentStorageClientBridge = $this
            ->getMockBuilder(ContentNavigationToContentStorageClientInterface::class)->getMock();
        $contentNavigationWidgetToContentStorageClientBridge
            ->method('findContentTypeContextByKey')
            ->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(
            ContentNavigationDependencyProvider::CLIENT_CONTENT_STORAGE,
            $contentNavigationWidgetToContentStorageClientBridge,
        );
    }

    /**
     * @return \Twig\TwigFunction|bool
     */
    protected function getContentNavigationTwigFunction()
    {
        $functionName = new ReflectionClassConstant(
            ContentNavigationTwigFunctionProvider::class,
            'TWIG_FUNCTION_NAME_CONTENT_NAVIGATION',
        );

        return $this->getTwig()->getFunction($functionName->getValue());
    }

    /**
     * @return \SprykerShop\Yves\ContentNavigationWidget\Plugin\Twig\ContentNavigationTwigPlugin
     */
    protected function createTwigPlugin(): ContentNavigationTwigPlugin
    {
        return new ContentNavigationTwigPlugin();
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwig(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)->setConstructorArgs([new FilesystemLoader()])
            ->onlyMethods(['render'])
            ->getMock();

        $twigMock->method('render')->willReturn(static::RENDERED_STRING);
        $twigPlugin = $this->createTwigPlugin();
        $twigPlugin->extend($twigMock, $this->getMockBuilder(ContainerInterface::class)->getMock());

        return $twigMock;
    }
}
