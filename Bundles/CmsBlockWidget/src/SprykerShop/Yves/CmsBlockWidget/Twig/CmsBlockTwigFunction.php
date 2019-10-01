<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Twig;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\SpyCmsBlockEntityTransfer;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface;
use Twig\Environment;

class CmsBlockTwigFunction extends TwigFunction
{
    protected const OPTION_NAME = 'name';
    protected const OPTION_KEY = 'key';

    protected const SPY_CMS_BLOCK_TWIG_FUNCTION = 'spyCmsBlock';

    /**
     * @var \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    protected $cmsBlockStorageClient;

    /**
     * @var \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface $cmsBlockStorageClient
     * @param \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface $storeClient
     * @param string $localeName
     */
    public function __construct(
        CmsBlockWidgetToCmsBlockStorageClientInterface $cmsBlockStorageClient,
        CmsBlockWidgetToStoreClientInterface $storeClient,
        string $localeName
    ) {
        parent::__construct();
        $this->cmsBlockStorageClient = $cmsBlockStorageClient;
        $this->storeClient = $storeClient;
        $this->localeName = $localeName;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::SPY_CMS_BLOCK_TWIG_FUNCTION;
    }

    /**
     * @return callable
     */
    public function getFunction()
    {
        return function (Environment $twig, array $context, array $blockOptions = []): ?string {
            $blocks = $this->getBlockDataByOptions($blockOptions);
            $rendered = '';

            foreach ($blocks as $block) {
                $blockData = $this->getCmsBlockTransfer($block);
                $isActive = $this->validateBlockTemplate($blockData);
                $isActive &= $this->validateDates($blockData);

                if ($isActive) {
                    $rendered .= $twig->render($blockData->getCmsBlockTemplate()->getTemplatePath(), [
                        'placeholders' => $this->getPlaceholders($blockData->getSpyCmsBlockGlossaryKeyMappings()),
                        'cmsContent' => $block,
                    ]);
                }
            }

            return $rendered;
        };
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'needs_context' => true,
            'needs_environment' => true,
            'is_safe' => ['html'],
        ];
    }

    /**
     * @param array $blockOptions
     *
     * @return array
     */
    protected function getBlockDataByOptions(array $blockOptions): array
    {
        $cmsBlockKey = $blockOptions[static::OPTION_KEY] ?? null;
        $storeName = $this->storeClient->getCurrentStore()->getName();

        if ($cmsBlockKey) {
            return $this->cmsBlockStorageClient->findBlocksByKeys([$cmsBlockKey], $this->localeName, $storeName);
        }

        $cmsBlockName = $blockOptions[static::OPTION_NAME] ?? null;

        if ($cmsBlockName) {
            return $this->cmsBlockStorageClient->findBlocksByNames([$cmsBlockName], $this->localeName, $storeName);
        }

        $availableBlockKeys = $this->cmsBlockStorageClient->findBlockKeysByOptions($blockOptions);

        return $this->cmsBlockStorageClient->findBlocksByKeys($availableBlockKeys, $this->localeName, $storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $cmsBlockData
     *
     * @return bool
     */
    protected function validateBlockTemplate(SpyCmsBlockEntityTransfer $cmsBlockData): bool
    {
        return $cmsBlockData->getCmsBlockTemplate() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $spyCmsBlockTransfer
     *
     * @return bool
     */
    protected function validateDates(SpyCmsBlockEntityTransfer $spyCmsBlockTransfer): bool
    {
        $dateToCompare = new DateTime();

        if ($spyCmsBlockTransfer->getValidFrom() !== null) {
            $validFrom = new DateTime($spyCmsBlockTransfer->getValidFrom());

            if ($dateToCompare < $validFrom) {
                return false;
            }
        }

        if ($spyCmsBlockTransfer->getValidTo() !== null) {
            $validTo = new DateTime($spyCmsBlockTransfer->getValidTo());

            if ($dateToCompare > $validTo) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject $mappings
     *
     * @return array
     */
    protected function getPlaceholders(ArrayObject $mappings): array
    {
        $placeholders = [];
        foreach ($mappings as $mapping) {
            $placeholders[$mapping->getPlaceholder()] = $mapping->getGlossaryKey()->getKey();
        }

        return $placeholders;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer
     */
    protected function getCmsBlockTransfer(array $data): SpyCmsBlockEntityTransfer
    {
        return (new SpyCmsBlockEntityTransfer())->fromArray($data, true);
    }
}
