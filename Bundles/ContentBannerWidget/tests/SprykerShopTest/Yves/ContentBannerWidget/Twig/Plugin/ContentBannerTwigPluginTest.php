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
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentBannerWidget\ContentBannerWidgetDependencyProvider;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;
use SprykerShop\Yves\ContentBannerWidget\Plugin\Twig\ContentBannerTwigPlugin;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
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
    protected const LOCALE = 'de_DE';

    protected const DEFAULT_TEMPLATE = 'default';
    protected const WRONG_TEMPLATE = 'wrong';

    protected const CONTENT_ID = 0;
    protected const CONTENT_TERM = 'TERM';

    protected const RENDERED_STRING = 'output';

    /**
     * @var \SprykerShopTest\Yves\ContentBannerWidget\ContentBannerWidgetYvesTester
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
    public function testContentBannerNotFound(): void
    {
        $contentBannerTwigPlugin = $this->getContentBannerTwigPlugin();
        $bannerContent = call_user_func($contentBannerTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);
        $messageBannerNotFound = new ReflectionClassConstant(ContentBannerTwigPlugin::class, 'MESSAGE_BANNER_NOT_FOUND');

        $this->assertEquals(
            '<!-- ' . sprintf($messageBannerNotFound->getValue(), static::CONTENT_ID) . ' -->',
            $bannerContent
        );
    }

    /**
     * @return void
     */
    public function testContentBannerWrongType(): void
    {
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->setIdContent(static::CONTENT_ID);
        $contentTypeContextTransfer->setTerm(static::CONTENT_TERM);

        $this->setContentBannerToContentStorageClientReturn($contentTypeContextTransfer);

        $contentBannerTwigPlugin = $this->getContentBannerTwigPlugin();
        $bannerContent = call_user_func($contentBannerTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);

        $messageBannerWrongType = new ReflectionClassConstant(ContentBannerTwigPlugin::class, 'MESSAGE_BANNER_WRONG_TYPE');
        $functionName = new ReflectionClassConstant(ContentBannerTwigPlugin::class, 'FUNCTION_NAME');

        $this->assertEquals(
            '<!-- ' . sprintf($messageBannerWrongType->getValue(), $functionName->getValue(), static::CONTENT_ID) . ' -->',
            $bannerContent
        );
    }

    /**
     * @return void
     */
    public function testContentBannerWrongTemplate(): void
    {
        $contentBannerTypeTransfer = new ContentBannerTypeTransfer();

        $this->setContentBannerWidgetToContentBannerClientReturn($contentBannerTypeTransfer);

        $contentBannerTwigPlugin = $this->getContentBannerTwigPlugin();

        $bannerContent = call_user_func($contentBannerTwigPlugin->getCallable(), static::CONTENT_ID, static::WRONG_TEMPLATE);

        $messageBannerWrongTemplate = new ReflectionClassConstant(ContentBannerTwigPlugin::class, 'MESSAGE_BANNER_WRONG_TEMPLATE');

        $this->assertEquals(
            '<!-- ' . sprintf(
                $messageBannerWrongTemplate->getValue(),
                static::WRONG_TEMPLATE
            ) . ' -->',
            $bannerContent
        );
    }

    /**
     * @return void
     */
    public function testContentBannerRendering(): void
    {
        $contentTypeContextTransfer = new ContentBannerTypeTransfer();

        $this->setContentBannerWidgetToContentBannerClientReturn($contentTypeContextTransfer);

        $contentBannerTwigPlugin = $this->getContentBannerTwigPlugin();

        $bannerContent = call_user_func($contentBannerTwigPlugin->getCallable(), static::CONTENT_ID, static::DEFAULT_TEMPLATE);

        $this->assertEquals($bannerContent, static::RENDERED_STRING);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer|null $contentBannerTypeTransfer
     *
     * @return void
     */
    protected function setContentBannerWidgetToContentBannerClientReturn(?ContentBannerTypeTransfer $contentBannerTypeTransfer = null): void
    {
        $contentBannerWidgetToContentBannerClientBridge = $this->getMockBuilder(ContentBannerWidgetToContentBannerClientInterface::class)->getMock();
        $contentBannerWidgetToContentBannerClientBridge->method('findBannerById')->willReturn($contentBannerTypeTransfer);
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
        $contentBannerWidgetToContentStorageClientBridge->method('findContentTypeContext')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentBannerDependencyProvider::CLIENT_CONTENT_STORAGE, $contentBannerWidgetToContentStorageClientBridge);
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentBannerTwigPlugin()
    {
        $functionName = new ReflectionClassConstant(ContentBannerTwigPlugin::class, 'FUNCTION_NAME');

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
            ->setMethods(['render'])
            ->getMock();

        $twigMock->method('render')->willReturn(static::RENDERED_STRING);
        $twigPlugin = $this->createTwigPlugin();
        $twigPlugin->extend($twigMock, $this->getMockBuilder(ContainerInterface::class)->getMock());

        return $twigMock;
    }
}
