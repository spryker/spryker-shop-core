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
        $fileStorageDataTransfer = (new FileStorageDataTransfer())
            ->setFileName($fileName)
            ->setType($fileType);

        $fileStorageDataTransfer = $this->createIconNameFileStorageDataExpander()->expand($fileStorageDataTransfer);

        $this->assertEquals($expectedFileIconName, $fileStorageDataTransfer->getIconName());
    }

    /**
     * @return array
     */
    public function fileDataProvider(): array
    {
        return [
            'type: text/plain, file name: test.csv, expected icon name: file-csv' => ['text/plain', 'test.csv', 'file-csv'],
            'type: image/png, file name: test.png, expected icon name: file-image' => ['image/png', 'test.png', 'file-image'],
            'type: image/jpeg, file name: test.jpeg, expected icon name: file-image' => ['image/jpeg', 'test.jpeg', 'file-image'],
            'type: video/mp4, file name: test.mp4, expected icon name: file-video' => ['video/mp4', 'test.mp4', 'file-video'],
            'type: audio/mp3, file name: test.mp3, expected icon name: file-audio' => ['audio/mp3', 'test.mp3', 'file-audio'],
            'type: application/pdf, file name: test.pdf, expected icon name: file-pdf' => ['application/pdf', 'test.pdf', 'file-pdf'],
            'type: text/plain, file name: test.txt, expected icon name: file' => ['text/plain', 'test.txt', 'file'],
            'type: test/test, file name: test.test, expected icon name: file' => ['test/test', 'test.test', 'file'],
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
