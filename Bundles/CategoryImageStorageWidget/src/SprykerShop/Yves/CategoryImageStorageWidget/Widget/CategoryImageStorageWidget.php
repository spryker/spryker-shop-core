<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageTransfer;
use Spryker\Shared\CategoryImageStorage\CategoryImageStorageConfig;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CategoryImageStorageWidget\CategoryImageStorageWidgetFactory getFactory()
 */
class CategoryImageStorageWidget extends AbstractWidget
{
    public const DEFAULT_CATEGORY_IMAGE = '';
    public const DEFAULT_IMAGE_SET_NAME = 'default';
    public const IMAGE_SIZE_SMALL = 'small';
    public const IMAGE_SIZE_LARGE = 'large';

    /**
     * @var array
     */
    protected $imageStorageTransferToSizeMap = [];

    /**
     * @param int $idCategory
     * @param string $categoryName
     * @param string $imageSetName
     * @param string $imageSize
     */
    public function __construct(
        int $idCategory,
        string $categoryName,
        string $imageSetName = self::DEFAULT_IMAGE_SET_NAME,
        string $imageSize = self::IMAGE_SIZE_SMALL
    ) {
        $this->imageStorageTransferToSizeMap = [
            static::IMAGE_SIZE_SMALL => CategoryImageStorageTransfer::EXTERNAL_URL_SMALL,
            static::IMAGE_SIZE_LARGE => CategoryImageStorageTransfer::EXTERNAL_URL_LARGE,
        ];

        $this->addParameter('categoryName', $categoryName)
            ->addParameter('imageUrl', $this->getCategoryImageUrl($idCategory, $imageSetName, $imageSize));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CategoryImageStorageWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CategoryImageStorageWidget/views/sub-category-image/sub-category-image.twig';
    }

    /**
     * @param int $idCategory
     * @param string $imageSetName
     * @param string $imageSize
     *
     * @return string
     */
    protected function getCategoryImageUrl(int $idCategory, string $imageSetName, string $imageSize): string
    {
        $categoryImageSetCollectionTransfer = $this->getCategoryImageSetCollectionTransfer($idCategory);
        if ($categoryImageSetCollectionTransfer === null) {
            return static::DEFAULT_CATEGORY_IMAGE;
        }

        $imageSetImages = $this->getImageSetImages($categoryImageSetCollectionTransfer->getImageSets(), $imageSetName);

        return $this->getImageUrlBySize($imageSetImages, $imageSize);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    protected function getCategoryImageSetCollectionTransfer(int $idCategory): ?CategoryImageSetCollectionStorageTransfer
    {
        return $this->getFactory()
            ->getCategoryImageStorageClient()
            ->findCategoryImageSetCollectionStorage($idCategory, $this->getLocale());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryImageSetStorageTransfer[] $imageSetStorageCollection
     * @param string $imageSetName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryImageStorageTransfer[]
     */
    protected function getImageSetImages($imageSetStorageCollection, string $imageSetName)
    {
        foreach ($imageSetStorageCollection as $categoryImageSetStorageTransfer) {
            if ($categoryImageSetStorageTransfer->getName() !== $imageSetName) {
                continue;
            }

            return $categoryImageSetStorageTransfer->getImages();
        }

        if ($imageSetName !== CategoryImageStorageConfig::DEFAULT_IMAGE_SET_NAME) {
            return $this->getImageSetImages($imageSetStorageCollection, CategoryImageStorageConfig::DEFAULT_IMAGE_SET_NAME);
        }

        if (isset($imageSetStorageCollection[0])) {
            return $imageSetStorageCollection[0]->getImages();
        }

        return new ArrayObject();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryImageStorageTransfer[] $imageSetImages
     * @param string $imageSize
     *
     * @return string
     */
    protected function getImageUrlBySize(ArrayObject $imageSetImages, string $imageSize): string
    {
        if (count($imageSetImages)) {
            $imageStorageTransferSizePropertyName = $this->getImageStorageTransferSizePropertyName($imageSize);

            return $imageSetImages[0][$imageStorageTransferSizePropertyName];
        }

        return static::DEFAULT_CATEGORY_IMAGE;
    }

    /**
     * @param string $imageSize
     *
     * @return string
     */
    protected function getImageStorageTransferSizePropertyName(string $imageSize): string
    {
        return $this->imageStorageTransferToSizeMap[$imageSize] ?? CategoryImageStorageTransfer::EXTERNAL_URL_SMALL;
    }
}
