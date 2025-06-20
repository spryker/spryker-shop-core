<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentBannerWidget\Twig\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentBanner\ContentBannerDependencyProvider;
use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ContentBannerWidget\ContentBannerWidgetDependencyProvider;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;
use SprykerShop\Yves\ContentBannerWidget\Plugin\Twig\ContentBannerTwigPlugin;
use SprykerShop\Yves\ContentBannerWidget\Twig\ContentBannerTwigFunctionProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentBannerWidget
 * @group Plugin
 * @group Twig
 * @group ContentBannerTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentBannerTwigPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_TEMPLATE = 'top-title';

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
    protected const MESSAGE_BANNER_NOT_FOUND = '<b>Content Banner with key 0 not found.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_BANNER_WRONG_TYPE = '<b>Content Banner could not be rendered because the content item with key 0 is not an banner.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_BANNER_WRONG_TEMPLATE = '<b>"wrong" is not supported name of template.</b>';

    /**
     * @var \SprykerShopTest\Yves\ContentBannerWidget\ContentBannerWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContentBannerNotFound(): void
    {
        // Arrange
        $this->setContentBannerToContentStorageClientReturn();

        // Act
        $bannerContent = call_user_func($this->getContentBannerTwigFunction()->getCallable(), static::CONTENT_KEY, static::DEFAULT_TEMPLATE);

        // Assert
        $this->assertSame(static::MESSAGE_BANNER_NOT_FOUND, $bannerContent);
    }

    /**
     * @return void
     */
    public function testContentBannerWrongType(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setKey(static::CONTENT_KEY);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);
        $this->setContentBannerToContentStorageClientReturn($contentTypeContextTransfer);

        // Act
        $bannerContent = call_user_func(
            $this->getContentBannerTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::DEFAULT_TEMPLATE,
        );

        // Assert

        $this->assertSame(static::MESSAGE_BANNER_WRONG_TYPE, $bannerContent);
    }

    /**
     * @return void
     */
    public function testContentBannerWrongTemplate(): void
    {
        // Assign
        $contentBannerTypeTransfer = new ContentBannerTypeTransfer();
        $this->setContentBannerWidgetToContentBannerClientReturn($contentBannerTypeTransfer);

        // Act
        $bannerContent = call_user_func(
            $this->getContentBannerTwigFunction()->getCallable(),
            static::CONTENT_KEY,
            static::WRONG_TEMPLATE,
        );

        // Assert
        $this->assertSame(static::MESSAGE_BANNER_WRONG_TEMPLATE, $bannerContent);
    }

    /**
     * @return void
     */
    public function testContentBannerRendering(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentBannerTypeTransfer();
        $this->setContentBannerWidgetToContentBannerClientReturn($contentTypeContextTransfer);

        // Act
        $bannerContent = call_user_func($this->getContentBannerTwigFunction()->getCallable(), static::CONTENT_KEY, static::DEFAULT_TEMPLATE);

        // Assert
        $this->assertSame(static::RENDERED_STRING, $bannerContent);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer|null $contentBannerTypeTransfer
     *
     * @return void
     */
    protected function setContentBannerWidgetToContentBannerClientReturn(?ContentBannerTypeTransfer $contentBannerTypeTransfer = null): void
    {
        $contentBannerWidgetToContentBannerClientBridge = $this->getMockBuilder(ContentBannerWidgetToContentBannerClientInterface::class)->getMock();
        $contentBannerWidgetToContentBannerClientBridge->method('executeBannerTypeByKey')->willReturn($contentBannerTypeTransfer);
        $this->tester->setDependency(ContentBannerWidgetDependencyProvider::CLIENT_CONTENT_BANNER, $contentBannerWidgetToContentBannerClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentBannerToContentStorageClientReturn(?ContentTypeContextTransfer $contentTypeContextTransfer = null): void
    {
        $contentBannerWidgetToContentStorageClientBridge = $this->getMockBuilder(ContentBannerToContentStorageClientInterface::class)->getMock();
        $contentBannerWidgetToContentStorageClientBridge->method('findContentTypeContextByKey')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentBannerDependencyProvider::CLIENT_CONTENT_STORAGE, $contentBannerWidgetToContentStorageClientBridge);
    }

    /**
     * @return \Twig\TwigFunction|bool
     */
    protected function getContentBannerTwigFunction()
    {
        $functionName = new ReflectionClassConstant(ContentBannerTwigFunctionProvider::class, 'TWIG_FUNCTION_NAME_CONTENT_BANNER');

        return $this->getTwig()->getFunction($functionName->getValue());
    }

    /**
     * @return \SprykerShop\Yves\ContentBannerWidget\Plugin\Twig\ContentBannerTwigPlugin
     */
    protected function createTwigPlugin(): ContentBannerTwigPlugin
    {
        return new ContentBannerTwigPlugin();
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
