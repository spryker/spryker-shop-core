<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopUI\Twig;

use Spryker\Shared\Twig\TwigExtension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

class ShopUITwigExtension extends TwigExtension
{
    const FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE = 'model';
    const FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE = 'atom';
    const FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE = 'molecule';
    const FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE = 'organism';
    const FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE = 'template';
    const FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE = 'view';
    const DEFAULT_MODULE = 'ShopUI';

    /**
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
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
                return $this->getViewTemplate($templateModule, $templateName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_VIEW_COMPONENT_TEMPLATE
            ]),
        ];
    }

    /**
     * @param String $modelName
     *
     * @return String
     */
    protected function getModelTemplate(String $modelName)
    {
        return '@ShopUI/models/' . $modelName . '.twig';
    }

    /**
     * @param String $componentModule
     * @param String $componentType
     * @param String $componentName
     *
     * @return String
     */
    protected function getComponentTemplate(String $componentModule, String $componentType, String $componentName)
    {
        return '@' . $componentModule . '/components/' . $componentType . '/' . $componentName . '/' . $componentName . '.twig';
    }

    protected function getTemplateTemplate(String $templateModule, String $templateName)
    {
        return '@' . $templateModule . '/templates/' . $templateName . '/' . $templateName . '.twig';
    }

    protected function getViewTemplate(String $viewModule, String $viewName)
    {
        return '@' . $viewModule . '/views/' . $viewName . '/' . $viewName . '.twig';
    }
}
