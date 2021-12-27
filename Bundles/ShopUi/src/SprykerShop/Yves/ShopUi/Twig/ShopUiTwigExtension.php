<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig;

use Spryker\Shared\Twig\TwigExtension;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientInterface;
use SprykerShop\Yves\ShopUi\ShopUiConfig;
use SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface;
use SprykerShop\Yves\ShopUi\Twig\Node\ShopUiDefineTwigNode;
use SprykerShop\Yves\ShopUi\Twig\TokenParser\ShopUiDefineTwigTokenParser;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ShopUiTwigExtension extends TwigExtension
{
    /**
     * @var string
     */
    public const FUNCTION_GET_PUBLIC_FOLDER_PATH = 'publicPath';

    /**
     * @var string
     */
    public const FUNCTION_GET_QA_ATTRIBUTE = 'qa';

    /**
     * @var string
     */
    public const FUNCTION_GET_QA_ATTRIBUTE_SUB = 'qa_*';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE = 'model';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE = 'atom';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE = 'molecule';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE = 'organism';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE = 'template';

    /**
     * @var string
     */
    public const FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE = 'view';

    /**
     * @var string
     */
    public const DEFAULT_MODULE = 'ShopUi';

    /**
     * @var string
     */
    protected const FILTER_TRIM_LOCALE = 'trimLocale';

    /**
     * @var \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface|null
     */
    protected $assetsUrlProvider;

    /**
     * @var string
     */
    protected $localesFilterPattern;

    /**
     * @var \SprykerShop\Yves\ShopUi\ShopUiConfig
     */
    protected $shopUiConfig;

    /**
     * @param \SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientInterface $localeClient
     * @param \SprykerShop\Yves\ShopUi\ShopUiConfig $shopUiConfig
     * @param \SprykerShop\Yves\ShopUi\Twig\Assets\AssetsUrlProviderInterface|null $assetsUrlProvider
     */
    public function __construct(
        ShopUiToLocaleClientInterface $localeClient,
        ShopUiConfig $shopUiConfig,
        ?AssetsUrlProviderInterface $assetsUrlProvider = null
    ) {
        $this->localeClient = $localeClient;
        $this->assetsUrlProvider = $assetsUrlProvider;
        $this->shopUiConfig = $shopUiConfig;
    }

    /**
     * @return array<string>
     */
    public function getGlobals(): array
    {
        return [
            'required' => ShopUiDefineTwigNode::REQUIRED_VALUE,
        ];
    }

    /**
     * @return array<\Twig\TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(static::FILTER_TRIM_LOCALE, function (string $filterValue): string {
                return $this->trimLocale($filterValue);
            }),
        ];
    }

    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(static::FUNCTION_GET_PUBLIC_FOLDER_PATH, function ($relativePath) {
                $publicFolderPath = $this->getPublicFolderPath();

                return $publicFolderPath . $relativePath;
            }, [
                $this,
                static::FUNCTION_GET_PUBLIC_FOLDER_PATH,
            ]),

            new TwigFunction(static::FUNCTION_GET_QA_ATTRIBUTE, function (array $qaValues = []) {
                return $this->getQaAttribute($qaValues);
            }, [
                $this,
                static::FUNCTION_GET_QA_ATTRIBUTE,
                'is_safe' => ['html'],
                'is_variadic' => true,
            ]),

            new TwigFunction(static::FUNCTION_GET_QA_ATTRIBUTE_SUB, function ($qaName, array $qaValues = []) {
                return $this->getQaAttribute($qaValues, $qaName);
            }, [
                $this,
                static::FUNCTION_GET_QA_ATTRIBUTE_SUB,
                'is_safe' => ['html'],
                'is_variadic' => true,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE, function ($modelName) {
                return $this->getModelTemplate($modelName);
            }, [
                $this,
                static::FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'atoms', $componentName);
            }, [
                $this,
                static::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'molecules', $componentName);
            }, [
                $this,
                static::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'organisms', $componentName);
            }, [
                $this,
                static::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE, function ($templateName, $templateModule = self::DEFAULT_MODULE) {
                return $this->getTemplateTemplate($templateModule, $templateName);
            }, [
                $this,
                static::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE,
            ]),

            new TwigFunction(static::FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE, function ($viewName, $viewModule = self::DEFAULT_MODULE) {
                return $this->getViewTemplate($viewModule, $viewName);
            }, [
                $this,
                static::FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE,
            ]),
        ];
    }

    /**
     * @return array<\Twig\TokenParser\AbstractTokenParser>
     */
    public function getTokenParsers(): array
    {
        return [
            new ShopUiDefineTwigTokenParser($this->shopUiConfig),
        ];
    }

    /**
     * @return string
     */
    protected function getPublicFolderPath(): string
    {
        if ($this->assetsUrlProvider) {
            return $this->assetsUrlProvider->getAssetsUrl();
        }

        return '/assets/';
    }

    /**
     * @param array $qaValues
     * @param string|null $qaName
     *
     * @return string
     */
    protected function getQaAttribute(array $qaValues = [], ?string $qaName = null): string
    {
        $value = '';

        if (!$qaValues) {
            return '';
        }

        foreach ($qaValues as $qaValue) {
            if ($qaValue) {
                $value .= $qaValue . ' ';
            }
        }

        if (!$qaName) {
            return 'data-qa="' . trim($value) . '"';
        }

        return 'data-qa-' . $qaName . '="' . trim($value) . '"';
    }

    /**
     * @param string $modelName
     *
     * @return string
     */
    protected function getModelTemplate(string $modelName): string
    {
        return '@ShopUi/models/' . $modelName . '.twig';
    }

    /**
     * @param string $componentModule
     * @param string $componentType
     * @param string $componentName
     *
     * @return string
     */
    protected function getComponentTemplate(string $componentModule, string $componentType, string $componentName): string
    {
        return '@' . $componentModule . '/components/' . $componentType . '/' . $componentName . '/' . $componentName . '.twig';
    }

    /**
     * @param string $templateModule
     * @param string $templateName
     *
     * @return string
     */
    protected function getTemplateTemplate(string $templateModule, string $templateName): string
    {
        return '@' . $templateModule . '/templates/' . $templateName . '/' . $templateName . '.twig';
    }

    /**
     * @param string $viewModule
     * @param string $viewName
     *
     * @return string
     */
    protected function getViewTemplate(string $viewModule, string $viewName): string
    {
        return '@' . $viewModule . '/views/' . $viewName . '/' . $viewName . '.twig';
    }

    /**
     * @param string $filterValue
     *
     * @return string
     */
    protected function trimLocale(string $filterValue): string
    {
        return preg_replace(
            $this->getLocalePattern(),
            '/',
            $filterValue,
        );
    }

    /**
     * @return string
     */
    protected function getLocalePattern(): string
    {
        if ($this->localesFilterPattern) {
            return $this->localesFilterPattern;
        }

        $locale = $this->localeClient->getCurrentLocale();
        $this->localesFilterPattern = '#^\/(' . strtok($locale, '_') . ')\/#';

        return $this->localesFilterPattern;
    }
}
