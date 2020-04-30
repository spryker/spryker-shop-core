<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentNavigationWidget\Twig\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentNavigation\ContentNavigationDependencyProvider;
use Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetDependencyProvider;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Plugin\Twig\ContentNavigationTwigPlugin;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction;
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
    protected const LOCALE = 'de_DE';

    protected const DEFAULT_TEMPLATE = 'top-title';
    protected const WRONG_TEMPLATE = 'wrong';

    protected const CONTENT_KEY = '0';
    protected const CONTENT_TERM = 'TERM';

    protected const RENDERED_STRING = 'output';

    protected const MESSAGE_NAVIGATION_NOT_FOUND = '<b>Content Navigation with key 0 not found.</b>';
    protected const MESSAGE_NAVIGATION_WRONG_TYPE = '<b>Content Navigation could not be rendered because the content item with key 0 is not an navigation.</b>';
    protected const MESSAGE_NAVIGATION_WRONG_TEMPLATE = '<b>"wrong" is not supported name of template.</b>';

    /**
     * @var \SprykerShopTest\Yves\ContentNavigationWidget\ContentNavigationWidgetYvesTester
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
    public function testContentNavigationNotFound(): void
    {
        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE
        );

        // Assert
        $this->assertEquals(static::MESSAGE_NAVIGATION_NOT_FOUND, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationWrongType(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setKey(static::CONTENT_KEY);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);
        $this->setContentNavigationToContentStorageClientReturn($contentTypeContextTransfer);

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE
        );

        // Assert

        $this->assertEquals(static::MESSAGE_NAVIGATION_WRONG_TYPE, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationWrongTemplate(): void
    {
        // Assign
        $contentNavigationTypeTransfer = new ContentNavigationTypeTransfer();
        $this->setContentNavigationWidgetToContentNavigationClientReturn($contentNavigationTypeTransfer);

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::WRONG_TEMPLATE
        );

        // Assert
        $this->assertEquals(static::MESSAGE_NAVIGATION_WRONG_TEMPLATE, $navigationContent);
    }

    /**
     * @return void
     */
    public function testContentNavigationRendering(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentNavigationTypeTransfer();
        $this->setContentNavigationWidgetToContentNavigationClientReturn($contentTypeContextTransfer);

        // Act
        $navigationContent = call_user_func(
            $this->getContentNavigationTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE
        );

        // Assert

        $this->assertEquals($navigationContent, static::RENDERED_STRING);
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
            ->method('executeNavigationTypeByKey')->willReturn($contentNavigationTypeTransfer);
        $this->tester->setDependency(
            ContentNavigationWidgetDependencyProvider::CLIENT_CONTENT_NAVIGATION,
            $contentNavigationWidgetToContentNavigationClientBridge
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
            ->method('findContentTypeContextByKey')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(
            ContentNavigationDependencyProvider::CLIENT_CONTENT_STORAGE,
            $contentNavigationWidgetToContentStorageClientBridge
        );
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentNavigationTwigFunction()
    {
        $functionName = new ReflectionClassConstant(
            ContentNavigationTwigFunction::class,
            'TWIG_FUNCTION_NAME_CONTENT_NAVIGATION'
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
            ->setMethods(['render'])
            ->getMock();

        $twigMock->method('render')->willReturn(static::RENDERED_STRING);
        $twigPlugin = $this->createTwigPlugin();
        $twigPlugin->extend($twigMock, $this->getMockBuilder(ContainerInterface::class)->getMock());

        return $twigMock;
    }
}
