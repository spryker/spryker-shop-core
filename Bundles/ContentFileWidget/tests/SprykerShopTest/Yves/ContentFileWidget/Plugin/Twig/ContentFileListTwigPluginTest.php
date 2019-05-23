<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentFileWidget\Plugin\Twig;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Generated\Shared\Transfer\FileStorageDataTransfer;
use ReflectionClassConstant;
use Spryker\Client\ContentFile\Exception\InvalidFileListTermException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\ContentFileWidget\ContentFileWidgetDependencyProvider;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;
use SprykerShop\Yves\ContentFileWidget\Plugin\Twig\ContentFileListTwigPlugin;
use SprykerShop\Yves\ContentFileWidget\Twig\ContentFileListTwigFunction;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
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
    protected const LOCALE = 'de_DE';

    protected const TEMPLATE_TEXT_LINK = 'text-link';
    protected const TEMPLATE_WRONG = 'wrong';

    protected const CONTENT_ID = 0;
    protected const CONTENT_TERM = 'TERM';

    protected const MESSAGE_CONTENT_FILE_LIST_NOT_FOUND = '<strong>Content file list with ID 0 not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE = '<strong>Content file list widget could not be rendered because the content item with ID 0 is not an file list.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"wrong" is not supported name of template.</strong>';
    protected const RENDERED_STRING = 'output';

    protected const FILE_SIZE = 1100;
    protected const FILE_TYPE = 'text/plain';
    protected const FILE_NAME = 'test.csv';

    /**
     * @var \SprykerShopTest\Yves\ContentFileWidget\ContentFileWidgetYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

//        Store::getInstance()->setCurrentLocale(static::LOCALE);
    }

    /**
     * @return void
     */
    public function testContentFileNotFound(): void
    {
        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_ID, static::TEMPLATE_TEXT_LINK);

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
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_ID, static::TEMPLATE_TEXT_LINK);

        // Assert
        $this->assertEquals(static::MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE, $fileContent);
    }

    /**
     * @return void
     */
    public function testContentFileWrongTemplate(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentFileListTypeTransfer();
        $contentTypeContextTransfer->setFileIds([static::CONTENT_ID]);
        $this->setContentFileClientReturn($contentTypeContextTransfer);
        $this->setFileStorageClientReturn();

        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_ID, static::TEMPLATE_WRONG);

        // Assert
        $this->assertEquals(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $fileContent);
    }

    /**
     * @return void
     */
    public function testContentFileRendering(): void
    {
        // Assign
        $contentTypeContextTransfer = new ContentFileListTypeTransfer();
        $contentTypeContextTransfer->setFileIds([static::CONTENT_ID]);
        $this->setContentFileClientReturn($contentTypeContextTransfer);
        $this->setFileStorageClientReturn();

        // Act
        $fileContent = call_user_func($this->getContentFileListTwigPlugin()->getCallable(), static::CONTENT_ID, static::TEMPLATE_TEXT_LINK);

        // Assert
        $this->assertEquals(static::RENDERED_STRING, $fileContent);
    }

    /**
     * @return void
     */
    protected function setContentFileClientException(): void
    {
        $contentFileWidgetToContentFileClientBridge = $this->getMockBuilder(ContentFileWidgetToContentFileClientInterface::class)->getMock();
        $contentFileWidgetToContentFileClientBridge->method('executeContentFileListTypeById')->willThrowException(new InvalidFileListTermException());
        $this->tester->setDependency(ContentFileWidgetDependencyProvider::CLIENT_CONTENT_FILE, $contentFileWidgetToContentFileClientBridge);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTypeTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setContentFileClientReturn(?ContentFileListTypeTransfer $contentTypeContextTransfer = null): void
    {
        $contentFileWidgetToContentFileClientBridge = $this->getMockBuilder(ContentFileWidgetToContentFileClientInterface::class)->getMock();
        $contentFileWidgetToContentFileClientBridge->method('executeContentFileListTypeById')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentFileWidgetDependencyProvider::CLIENT_CONTENT_FILE, $contentFileWidgetToContentFileClientBridge);
    }

    /**
     * @return void
     */
    protected function setFileStorageClientReturn(): void
    {
        $contentFileWidgetToFileStorageClientBridge = $this->getMockBuilder(ContentFileWidgetToFileManagerStorageClientInterface::class)->getMock();
        $contentFileWidgetToFileStorageClientBridge->method('findFileById')->willReturn(
            (new FileStorageDataTransfer())
                ->setSize(static::FILE_SIZE)
                ->setType(static::FILE_TYPE)
                ->setFileName(static::FILE_NAME)
        );
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
