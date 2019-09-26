<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ContentFileWidget\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileStorageDataTransfer;
use SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig;
use SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface;
use SprykerShop\Yves\ContentFileWidget\Expander\IconNameFileStorageDataExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShop
 * @group Yves
 * @group ContentFileWidget
 * @group Expander
 * @group IconNameFileStorageDataExpanderTest
 * Add your own group annotations below this line
 */
class IconNameFileStorageDataExpanderTest extends Unit
{
    /**
     * @dataProvider fileDataProvider
     *
     * @param string $fileType
     * @param string $fileName
     * @param string $expectedFileIconName
     *
     * @return void
     */
    public function testGetIconNameForFile(string $fileType, string $fileName, string $expectedFileIconName): void
    {
        // Arrange
        $fileStorageDataTransfer = (new FileStorageDataTransfer())
            ->setFileName($fileName)
            ->setType($fileType);

        // Act
        $fileStorageDataTransfer = $this->createIconNameFileStorageDataExpander()->expand($fileStorageDataTransfer);

        // Assert
        $this->assertEquals($expectedFileIconName, $fileStorageDataTransfer->getIconName());
    }

    /**
     * @return array
     */
    public function fileDataProvider(): array
    {
        return [
            ['application/csv', 'test.csv', 'file-csv'],
            ['application/gzip', 'test.gz', 'file-archive'],
            ['application/msword', 'test.doc', 'file-word'],
            ['application/pdf', 'test.pdf', 'file-pdf'],
            ['application/vnd.ms-word', 'test.docx', 'file-word'],
            ['application/x-csv', 'test.csv', 'file-csv'],
            ['application/x-zip-compressed', 'test.zip', 'file-archive'],
            ['application/zip', 'test.zip', 'file-archive'],
            ['audio/mp3', 'test.mp3', 'file-audio'],
            ['image/gif', 'test.gif', 'file-image'],
            ['image/jpeg', 'test.jpeg', 'file-image'],
            ['image/jpg', 'test.jpg', 'file-image'],
            ['image/png', 'test.png', 'file-image'],
            ['test/test', 'test.test', 'file'],
            ['text/csv', 'test.csv', 'file-csv'],
            ['text/comma-separated-values', 'test.csv', 'file-csv'],
            ['text/plain', 'test.txt', 'file-text'],
            ['text/tab-separated-values', 'test.csv', 'file-csv'],
            ['text/x-comma-separated-values', 'test.csv', 'file-csv'],
            ['text/x-csv', 'test.csv', 'file-csv'],
            ['image/tiff', 'test.tiff', 'file-image'],
            ['video/mp4', 'test.mp4', 'file-video'],
        ];
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface
     */
    protected function createIconNameFileStorageDataExpander(): FileStorageDataExpanderInterface
    {
        return new IconNameFileStorageDataExpander($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig
     */
    protected function getConfig(): ContentFileWidgetConfig
    {
        return new ContentFileWidgetConfig();
    }
}
