<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Business;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException;
use Twig\Environment as TwigEnvironment;

class CmsSlotBlockWidgetDataProvider implements CmsSlotBlockWidgetDataProviderInterface
{
    /**
     * @uses \Spryker\Client\CmsBlockStorage\Storage\CmsBlockStorage::OPTION_KEYS
     *
     * @var string
     */
    protected const KEY_BLOCK_OPTIONS_KEYS = 'keys';

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
     */
    protected $cmsSlotBlockStorageClient;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface
     */
    protected $cmsSlotBlockClient;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig
     */
    protected $cmsSlotBlockWidgetConfig;

    /**
     * @param \Twig\Environment $twigEnvironment
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface $cmsSlotBlockStorageClient
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface $cmsSlotBlockClient
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig $cmsSlotBlockWidgetConfig
     */
    public function __construct(
        TwigEnvironment $twigEnvironment,
        CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface $cmsSlotBlockStorageClient,
        CmsSlotBlockWidgetToCmsSlotBlockClientInterface $cmsSlotBlockClient,
        CmsSlotBlockWidgetConfig $cmsSlotBlockWidgetConfig
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->cmsSlotBlockStorageClient = $cmsSlotBlockStorageClient;
        $this->cmsSlotBlockClient = $cmsSlotBlockClient;
        $this->cmsSlotBlockWidgetConfig = $cmsSlotBlockWidgetConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        $cmsBlockTwigFunction = $this->getCmsBlockTwigFunction();

        $content = $this->getContent($cmsBlockTwigFunction, $cmsSlotContentRequestTransfer);

        return (new CmsSlotContentResponseTransfer())
            ->setContent($content);
    }

    /**
     * @throws \SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException
     *
     * @return callable
     */
    protected function getCmsBlockTwigFunction(): callable
    {
        $twigFunction = $this->twigEnvironment->getFunction($this->cmsSlotBlockWidgetConfig->getCmsBlockTwigFunctionName());

        if (!$twigFunction) {
            throw new CmsBlockTwigFunctionMissingException(sprintf(
                'Twig function with name %s is not registered in TwigDependencyProvider::getTwigPlugins().',
                $this->cmsSlotBlockWidgetConfig->getCmsBlockTwigFunctionName(),
            ));
        }

        /**
         * @var callable $callable
         */
        $callable = $twigFunction->getCallable();

        return $callable;
    }

    /**
     * @param callable $cmsBlockFunction
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return string
     */
    protected function getContent(
        callable $cmsBlockFunction,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): string {
        $cmsSlotBlockCollectionTransfer = $this->cmsSlotBlockStorageClient
            ->getCmsSlotBlockCollection(
                $cmsSlotContentRequestTransfer->getCmsSlotTemplatePath(),
                $cmsSlotContentRequestTransfer->getCmsSlotKey(),
            );

        if ($cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->count() === 0) {
            return '';
        }

        $keys = $this->getVisibleBlockKeys(
            $cmsSlotBlockCollectionTransfer,
            $cmsSlotContentRequestTransfer,
        );
        $blockOptions = [
            static::KEY_BLOCK_OPTIONS_KEYS => $keys,
        ];

        return $cmsBlockFunction($this->twigEnvironment, [], $blockOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return array
     */
    protected function getVisibleBlockKeys(
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): array {
        $visibleBlockKeys = [];

        foreach ($cmsSlotBlockCollectionTransfer->getCmsSlotBlocks() as $cmsSlotBlockTransfer) {
            $isCmsBlockVisibleInSlot = $this->cmsSlotBlockClient->isCmsBlockVisibleInSlot(
                $cmsSlotBlockTransfer,
                $cmsSlotContentRequestTransfer->getCmsSlotParams(),
            );

            if ($isCmsBlockVisibleInSlot) {
                $visibleBlockKeys[] = $cmsSlotBlockTransfer->getCmsBlockKey();
            }
        }

        return $visibleBlockKeys;
    }
}
