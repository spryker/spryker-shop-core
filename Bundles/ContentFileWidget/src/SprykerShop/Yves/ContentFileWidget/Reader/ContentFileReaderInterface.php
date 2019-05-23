<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Reader;

interface ContentFileReaderInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array
     */
    public function getFileCollection(int $idContent, string $localeName): array;
}
