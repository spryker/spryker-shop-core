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
                return $this->getComponentTemplate('atoms', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ATOM_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('molecules', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_MOLECULE_COMPONENT_TEMPLATE
            ]),

            new Twig_SimpleFunction(self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE, function ($componentName) {
                return $this->getComponentTemplate('organisms', $componentName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_ORGANISM_COMPONENT_TEMPLATE
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
     * @param String $componentType
     * @param String $componentName
     *
     * @return String
     */
    protected function getComponentTemplate(String $componentFolder, String $componentName)
    {
        return '@ShopUI/components/' . $componentFolder . '/' . $componentName . '/' . $componentName . '.twig';
    }
}
