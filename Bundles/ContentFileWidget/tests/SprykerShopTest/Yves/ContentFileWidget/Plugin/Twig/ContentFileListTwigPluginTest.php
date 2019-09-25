<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentFileWidget\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentFile\Exception\InvalidFileListTermException;
use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\ContentFileWidget\ContentFileWidgetDependencyProvider;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;
use SprykerShop\Yves\ContentFileWidget\Plugin\Twig\ContentFileListTwigPlugin;
use SprykerShop\Yves\ContentFileWidget\Twig\ContentFileListTwigFunction;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentFileWidget
 * @group Plugin
 * @group Twig
 * @group ContentFileListTwigPluginTest
 * Add your own group annotations below this line
 */
class ContentFileListTwigPluginTest extends Unit
{
    protected const TEMPLATE_TEXT_LINK = 'text-link';
    protected const TEMPLATE_WRONG = 'wrong';

    protected const CONTENT_WRONG_KEY = 'fl-0';
    protected const CONTENT_TERM = 'TERM';

    protected const MESSAGE_CONTENT_FILE_LIST_NOT_FOUND = '<strong>Content file list with key "fl-0" not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE = '<strong>Content file list widget could not be rendered because the content item with key "fl-0" is not an file list.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"wrong" is not supported name of template.</strong>';
    protected const RENDERED_STRING = 'output';

    /**
     * @var \SprykerShopTest\Yves\ContentFileWidget\ContentFileWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContentFileNotFound(): void
    {
        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_WRONG_KEY, static::TEMPLATE_TEXT_LINK);

        // Assert
        $this->assertEquals(static::MESSAGE_CONTENT_FILE_LIST_NOT_FOUND, $fileContent);
    }

    /**
     * @return void
     */
    public function testContentFileWrongType(): void
    {
        // Assign
        $this->setContentFileClientException();

        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_WRONG_KEY, static::TEMPLATE_TEXT_LINK);

        // Assert
        $this->assertEquals(static::MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE, $fileContent);
    }

    /**
     * @return void
     */
    public function testContentFileWrongTemplate(): void
    {
        // Assign
        $contentFileListTypeTransfer = new ContentFileListTypeTransfer();
        $contentFileListTypeTransfer->setFileIds([0]);
        $this->setContentFileClientReturn($contentFileListTypeTransfer);

        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_WRONG_KEY, static::TEMPLATE_WRONG);

        // Assert
        $this->assertEquals(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $fileContent);
    }

    /**
     * @return void
     */
    public function testContentFileRendering(): void
    {
        // Assign
        $contentFileListTypeTransfer = new ContentFileListTypeTransfer();
        $contentFileListTypeTransfer->setFileIds([0]);
        $this->setContentFileClientReturn($contentFileListTypeTransfer);
        $this->setFileManagerStorageClientReturn();

        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_WRONG_KEY, static::TEMPLATE_TEXT_LINK);

        // Assert
        $this->assertEquals(static::RENDERED_STRING, $fileContent);
    }

    /**
     * @return void
     */
    protected function setContentFileClientException(): void
    {
        $contentFileWidgetToContentFileClientBridge = $this->getMockBuilder(ContentFileWidgetToContentFileClientInterface::class)->getMock();
        $contentFileWidgetToContentFileClientBridge->method('executeFileListTypeByKey')->willThrowException(new InvalidFileListTermException());
        $this->tester->setDependency(ContentFileWidgetDependencyProvider::CLIENT_CONTENT_FILE, $contentFileWidgetToContentFileClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTypeTransfer|null $contentFileListTypeTransfer
     *
     * @return void
     */
    protected function setContentFileClientReturn(?ContentFileListTypeTransfer $contentFileListTypeTransfer = null): void
    {
        $contentFileWidgetToContentFileClientBridge = $this->getMockBuilder(ContentFileWidgetToContentFileClientInterface::class)->getMock();
        $contentFileWidgetToContentFileClientBridge->method('executeFileListTypeByKey')->willReturn($contentFileListTypeTransfer);
        $this->tester->setDependency(ContentFileWidgetDependencyProvider::CLIENT_CONTENT_FILE, $contentFileWidgetToContentFileClientBridge);
    }

    /**
     * @return void
     */
    protected function setFileManagerStorageClientReturn(): void
    {
        $contentFileWidgetToFileStorageClientBridge = $this->getMockBuilder(ContentFileWidgetToFileManagerStorageClientInterface::class)->getMock();
        $contentFileWidgetToFileStorageClientBridge->method('findFileById')->willReturn(null);
        $this->tester->setDependency(ContentFileWidgetDependencyProvider::CLIENT_FILE_MANAGER_STORAGE, $contentFileWidgetToFileStorageClientBridge);
    }

    /**
     * @return bool|\Twig\TwigFunction
     */
    protected function getContentFileListTwigPlugin()
    {
        $functionName = new ReflectionClassConstant(ContentFileListTwigFunction::class, 'FUNCTION_CONTENT_FILE_LIST');

        return $this->getTwig()->getFunction($functionName->getValue());
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Plugin\Twig\ContentFileListTwigPlugin
     */
    protected function createTwigPlugin(): ContentFileListTwigPlugin
    {
        return new ContentFileListTwigPlugin();
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
