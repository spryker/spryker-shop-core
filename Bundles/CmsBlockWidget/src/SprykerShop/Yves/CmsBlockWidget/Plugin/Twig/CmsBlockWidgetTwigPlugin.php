<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SpyCmsBlockEntityTransfer;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockWidgetTwigPlugin extends AbstractTwigExtensionPlugin
{
    protected const OPTION_NAME = 'name';
    protected const OPTION_KEY = 'key';

    protected const SERVICE_STORE = 'store';
    protected const SERVICE_LOCALE = 'locale';
    protected const SPY_CMS_BLOCK_TWIG_FUNCTION = 'spyCmsBlock';

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(static::SPY_CMS_BLOCK_TWIG_FUNCTION, [
                $this,
                'renderCmsBlock',
            ], [
                'needs_context' => true,
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param array $context
     * @param array $blockOptions
     *
     * @return string
     */
    public function renderCmsBlock(Environment $twig, array $context, array $blockOptions = []): string
    {
        $blocks = $this->getBlockDataByOptions($blockOptions);
        $rendered = '';

        foreach ($blocks as $block) {
            $blockData = $this->getCmsBlockTransfer($block);
            $isActive = $this->validateBlock($blockData);
            $isActive &= $this->validateDates($blockData);

            if ($isActive) {
                $rendered .= $twig->render($blockData->getCmsBlockTemplate()->getTemplatePath(), [
                    'placeholders' => $this->getPlaceholders($blockData->getSpyCmsBlockGlossaryKeyMappings()),
                    'cmsContent' => $block,
                ]);
            }
        }

        return $rendered;
    }

    /**
     * @param array $blockOptions
     *
     * @return array
     */
    protected function getBlockDataByOptions(array &$blockOptions): array
    {
        $localeName = $this->getLocaleName();
        $storeName = $this->getStoreName();
        $cmsBlockStorageClient = $this->getFactory()->getCmsBlockStorageClient();

        $cmsBlockKey = $blockOptions[static::OPTION_KEY] ?? null;

        if ($cmsBlockKey) {
            return $cmsBlockStorageClient->findBlocksByKeys([$cmsBlockKey], $localeName, $storeName);
        }

        $cmsBlockName = $blockOptions[static::OPTION_NAME] ?? null;

        if ($cmsBlockName) {
            return $cmsBlockStorageClient->findBlocksByNames([$cmsBlockName], $localeName, $storeName);
        }

        $availableBlockKeys = $cmsBlockStorageClient->findBlockKeysByOptions($blockOptions);

        return $cmsBlockStorageClient->findBlocksByKeys($availableBlockKeys, $localeName, $storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $cmsBlockData
     *
     * @return bool
     */
    protected function validateBlock(SpyCmsBlockEntityTransfer $cmsBlockData): bool
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

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        return $this->getApplication()->get(static::SERVICE_LOCALE);
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        return $this->getApplication()->get(static::SERVICE_STORE);
    }
}
