<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopUI\Twig;

use Spryker\Shared\Twig\TwigExtension;
use Twig_SimpleFunction;

class ShopUITwigExtension extends TwigExtension
{
    const FUNCTION_GET_UI_MODEL_COMPONENT_TEMPLATE = 'model';
    const FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE = 'atom';
    const FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE = 'molecule';
    const FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE = 'organism';
    const FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE = 'template';

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

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('Atom', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('Molecule', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('Organism', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('Template', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE
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
        return '@ShopUI/Model/' . $modelName . '.twig';
    }

    /**
     * @param String $componentType
     * @param String $componentName
     *
     * @return String
     */
    protected function getComponentTemplate(String $componentType, String $componentName)
    {
        return '@ShopUI/Component/' . $componentType . '/' . $componentName . '/' . $componentName . '.twig';
    }
}
