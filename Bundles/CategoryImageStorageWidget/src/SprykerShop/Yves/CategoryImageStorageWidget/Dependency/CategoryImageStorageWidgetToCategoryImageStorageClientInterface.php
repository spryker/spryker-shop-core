<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget\Dependency;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;

interface CategoryImageStorageWidgetToCategoryImageStorageClientInterface
{
    /**
     * @param int $categoryId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    public function findCategoryImageSetCollectionStorage(int $categoryId, string $localeName): ?CategoryImageSetCollectionStorageTransfer;
}
