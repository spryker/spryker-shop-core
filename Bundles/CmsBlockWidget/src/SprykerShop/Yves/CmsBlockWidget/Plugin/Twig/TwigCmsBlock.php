<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SpyCmsBlockTransfer;
use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class TwigCmsBlock extends AbstractPlugin implements TwigFunctionPluginInterface
{
    const OPTION_NAME = 'name';
    const OPTION_POSITION = 'position';

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        $this->localeName = $application['locale'];

        return [
            new Twig_SimpleFunction('spyCmsBlock', [
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
     * @param \Twig_Environment $twig
     * @param array $context
     * @param array $blockOptions
     *
     * @return string
     */
    public function renderCmsBlock(Twig_Environment $twig, array $context, array $blockOptions = [])
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
     * @return \Generated\Shared\Transfer\SpyCmsBlockTransfer[]
     */
    protected function getBlockDataByOptions(array &$blockOptions)
    {
        $blockName = $this->extractBlockNameOption($blockOptions);
        $positionName = $this->extractPositionOption($blockOptions);

        $availableBlockNames = $this->getFactory()->getCmsBlockStorageClient()->findBlockNamesByOptions($blockOptions, $this->localeName);
        $availableBlockNames = $this->filterPosition($positionName, $availableBlockNames);
        $availableBlockNames = $this->filterAvailableBlockNames($blockName, $availableBlockNames);

        return $this->getFactory()->getCmsBlockStorageClient()->findBlocksByNames($availableBlockNames, $this->localeName);
    }

    /**
     * @param array $blockOptions
     *
     * @return string
     */
    protected function extractPositionOption(array &$blockOptions)
    {
        $positionName = isset($blockOptions[static::OPTION_POSITION]) ? $blockOptions[static::OPTION_POSITION] : '';
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
    protected function filterPosition($positionName, array $availableBlockNames)
    {
        if (is_array(current($availableBlockNames))) {
            return isset($availableBlockNames[$positionName]) ? $availableBlockNames[$positionName] : [];
        }

        return $availableBlockNames;
    }

    /**
     * @param string $blockName
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterAvailableBlockNames($blockName, array $availableBlockNames)
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
    protected function createBlockTransfer()
    {
        $cmsBlockTransfer = new CmsBlockTransfer();

        return $cmsBlockTransfer;
    }

    /**
     * @param array $blockOptions
     *
     * @return string
     */
    protected function extractBlockNameOption(array &$blockOptions)
    {
        $blockName = isset($blockOptions[static::OPTION_NAME]) ? $blockOptions[static::OPTION_NAME] : null;
        unset($blockOptions[static::OPTION_NAME]);

        return $blockName;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockTransfer $cmsBlockData
     *
     * @return bool
     */
    protected function validateBlock(SpyCmsBlockTransfer $cmsBlockData)
    {
        return !($cmsBlockData === null);
    }

    /**
     * @param string $blockName
     *
     * @return string
     */
    protected function generateBlockNameKey($blockName)
    {
        return $this->getFactory()->getCmsBlockStorageClient()->generateBlockNameKey($blockName);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockTransfer $spyCmsBlockTransfer
     *
     * @return bool
     */
    protected function validateDates(SpyCmsBlockTransfer $spyCmsBlockTransfer)
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
    protected function getPlaceholders(ArrayObject $mappings)
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
     * @return \Generated\Shared\Transfer\SpyCmsBlockTransfer
     */
    protected function getCmsBlockTransfer(array $data)
    {
        return (new SpyCmsBlockTransfer())->fromArray($data, true);
    }
}
