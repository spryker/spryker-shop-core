<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopTemplateUI\Twig;

use Spryker\Shared\Twig\TwigExtension;
use Twig_SimpleFunction;

class ShopTemplateUITwigExtension extends TwigExtension
{
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
            new Twig_SimpleFunction(self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE, function ($templateType, $templateName) {
                return $this->getTemplateTemplate($templateType, $templateName);
            }, [
                $this, 
                self::FUNCTION_GET_UI_TEMPLATE_COMPONENT_TEMPLATE
            ]),
        ];
    }

    /**
     * @param String $templateType
     * @param String $templateName
     *
     * @return String
     */
    protected function getTemplateTemplate(String $templateType, String $templateName)
    {
        return '@ShopTemplateUI/templates/' . $templateType . '/' . $templateName . '/' . $templateName . '.twig';
    }
}
