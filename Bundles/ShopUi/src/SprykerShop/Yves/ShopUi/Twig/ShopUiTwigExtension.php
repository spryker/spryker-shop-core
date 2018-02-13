<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig;

use SprykerShop\Yves\ShopUi\Twig\Tag\ShopUiDefineTwigTokenParser;
use SprykerShop\Yves\ShopUi\Twig\Tag\ShopUiDefineTwigNode;
use Spryker\Shared\Twig\TwigExtension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;
use Twig_Environment;
use Twig_Error_Runtime;

class ShopUiTwigExtension extends TwigExtension
{
    const FUNCTION_GET_PUBLIC_FOLDER_PATH = 'publicPath';
    const FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE = 'model';
    const FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE = 'atom';
    const FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE = 'molecule';
    const FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE = 'organism';
    const FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE = 'template';
    const FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE = 'view';
    const DEFAULT_MODULE = 'ShopUi';

    /**
     * @return String[]
     */
    public function getGlobals()
    {
        return [
            'required' => ShopUiDefineTwigNode::REQUIRED_VALUE,
        ];
    }

    /**
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * @return Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(self::FUNCTION_GET_PUBLIC_FOLDER_PATH, function ($relativePath) {
                $publicFolderPath = $this->getPublicFolderPath();
                return $publicFolderPath . $relativePath;
            }, [
                $this, 
                self::FUNCTION_GET_PUBLIC_FOLDER_PATH
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE, function ($modelName) {
                return $this->getModelTemplate($modelName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'atoms', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'molecules', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE, function ($componentName, $componentModule = self::DEFAULT_MODULE) {
                return $this->getComponentTemplate($componentModule, 'organisms', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE, function ($templateName, $templateModule = self::DEFAULT_MODULE) {
                return $this->getTemplateTemplate($templateModule, $templateName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE, function ($viewName, $viewModule = self::DEFAULT_MODULE) {
                return $this->getViewTemplate($viewModule, $viewName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE
            ]),
        ];
    }

    /**
     * @return Twig_TokenParser[]
     */
    public function getTokenParsers()
    {
        return [
            new ShopUiDefineTwigTokenParser(),
        ];
    }

    /**
     * @return String
     */
    protected function getPublicFolderPath(): string
    {
        return '/assets/';
    }

    /**
     * @param String $modelName
     *
     * @return String
     */
    protected function getModelTemplate(string $modelName): string
    {
        return '@ShopUi/models/' . $modelName . '.twig';
    }

    /**
     * @param String $componentModule
     * @param String $componentType
     * @param String $componentName
     *
     * @return String
     */
    protected function getComponentTemplate(string $componentModule, string $componentType, string $componentName): string
    {
        return '@' . $componentModule . '/components/' . $componentType . '/' . $componentName . '/' . $componentName . '.twig';
    }

    /**
     * @param String $templateModule
     * @param String $templateName
     *
     * @return String
     */
    protected function getTemplateTemplate(string $templateModule, string $templateName): string
    {
        return '@' . $templateModule . '/templates/' . $templateName . '/' . $templateName . '.twig';
    }

    /**
     * @param String $viewModule
     * @param String $viewName
     *
     * @return String
     */
    protected function getViewTemplate(string $viewModule, string $viewName): string
    {
        return '@' . $viewModule . '/views/' . $viewName . '/' . $viewName . '.twig';
    }
}
