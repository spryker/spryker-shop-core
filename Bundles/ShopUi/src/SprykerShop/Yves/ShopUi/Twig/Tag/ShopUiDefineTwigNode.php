<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Tag;

use Twig_Node;
use Twig_Node_Expression;
use Twig_Compiler;
use Twig_Error_Runtime;

class ShopUiDefineTwigNode extends Twig_Node
{
    const REQUIRED_VALUE = '___REQUIRED___';

    public function __construct($name, Twig_Node_Expression $value, $line, $tag = null)
    {
        parent::__construct(['value' => $value], ['name' => $name], $line, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        $key = "'".$this->getAttribute('name')."'";
        $requiredValue = "'".self::REQUIRED_VALUE."'";

        $compiler
            ->addDebugInfo($this)
            ->raw('if (!array_key_exists('.$key.', $context)) {')
            ->raw('$context['.$key.'] = [];')
            ->raw('}')
            ->raw('$context['.$key.'] = array_replace_recursive(')
            ->subcompile($this->getNode('value'))
            ->raw(', $context['.$key.']);')
            ->raw('array_walk_recursive($context['.$key.'], function($value, $key) {')
            ->raw('if ($value === '.$requiredValue.') {')
            ->raw('throw new Twig_Error_Runtime(\'required '.$this->getAttribute('name').' property "\'.$key.\'" is not defined for "'.$this->getTemplateName().'"\'); }')
            ->raw('});');
    }
}
