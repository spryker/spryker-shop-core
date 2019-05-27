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
            'text/plain', 'test.csv', 'file-csv',
            'image/png', 'test.png', 'file-image',
            'image/jpeg', 'test.jpeg', 'file-image',
            'video/mp4', 'test.mp4', 'file-video',
            'audio/mp3', 'test.mp3', 'file-audio',
            'application/pdf', 'test.pdf', 'file-pdf',
            'text/plain', 'test.txt', 'file',
            'test/test', 'test.test', 'file',
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
