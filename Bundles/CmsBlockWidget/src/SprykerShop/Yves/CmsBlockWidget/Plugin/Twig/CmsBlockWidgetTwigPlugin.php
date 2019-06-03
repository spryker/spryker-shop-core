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
    public const OPTION_NAME = 'name';
    public const OPTION_POSITION = 'position';

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
        $blockName = $this->extractBlockNameOption($blockOptions);
        $positionName = $this->extractPositionOption($blockOptions);

        $localeName = $this->getLocaleName();
        $storeName = $this->getStoreName();

        $availableBlockNames = $this->getFactory()->getCmsBlockStorageClient()->findBlockNamesByOptions($blockOptions, $localeName);
        $availableBlockNames = $this->filterPosition($positionName, $availableBlockNames);
        $availableBlockNames = $this->filterAvailableBlockNames($blockName, $availableBlockNames);

        return $this->getFactory()->getCmsBlockStorageClient()->findBlocksByNames($availableBlockNames, $localeName, $storeName);
    }

    /**
     * @param array $blockOptions
     *
     * @return string
     */
    protected function extractPositionOption(array &$blockOptions): string
    {
        $positionName = $blockOptions[static::OPTION_POSITION] ?? '';
        $positionName = strtolower($positionName);
        unset($blockOptions[static::OPTION_POSITION]);

        return $positionName;
    }

    /**
     * @param string $positionName
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterPosition(string $positionName, array $availableBlockNames): array
    {
        if (is_array(current($availableBlockNames))) {
            return $availableBlockNames[$positionName] ?? [];
        }

        return $availableBlockNames;
    }

    /**
     * @param string|null $blockName
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterAvailableBlockNames(?string $blockName, array $availableBlockNames): array
    {
        $blockNameKey = $this->generateBlockNameKey($blockName);

        if ($blockNameKey) {
            if (!$availableBlockNames || in_array($blockNameKey, $availableBlockNames)) {
                $availableBlockNames = [$blockNameKey];
            } else {
                $availableBlockNames = [];
            }
        }

        return $availableBlockNames;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function createBlockTransfer(): CmsBlockTransfer
    {
        $cmsBlockTransfer = new CmsBlockTransfer();

        return $cmsBlockTransfer;
    }

    /**
     * @param array $blockOptions
     *
     * @return string|null
     */
    protected function extractBlockNameOption(array &$blockOptions): ?string
    {
        $blockName = $blockOptions[static::OPTION_NAME] ?? null;
        unset($blockOptions[static::OPTION_NAME]);

        return $blockName;
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
     * @param string|null $blockName
     *
     * @return string
     */
    protected function generateBlockNameKey(?string $blockName): string
    {
        return $this->getFactory()->getCmsBlockStorageClient()->generateBlockNameKey($blockName);
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
