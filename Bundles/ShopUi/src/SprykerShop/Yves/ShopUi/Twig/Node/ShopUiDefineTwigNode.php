<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Node;

use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

class ShopUiDefineTwigNode extends Twig_Node
{
    const REQUIRED_VALUE = '___REQUIRED___';

    /**
     * @param string $name
     * @param \Twig_Node_Expression $value
     * @param int $line
     * @param string|null $tag
     */
    public function __construct(string $name, Twig_Node_Expression $value, int $line, string $tag = null)
    {
        parent::__construct(['value' => $value], ['name' => $name], $line, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     *
     * @return void
     */
    public function compile(Twig_Compiler $compiler): void
    {
        $key = "'" . $this->getAttribute('name') . "'";
        $requiredValue = "'" . self::REQUIRED_VALUE . "'";

        $compiler
            ->addDebugInfo($this)
            ->raw('if (!array_key_exists(' . $key . ', $context)) {')
            ->raw('$context[' . $key . '] = [];')
            ->raw('}')
            ->raw('$context[' . $key . '] = array_replace_recursive(')
            ->subcompile($this->getNode('value'))
            ->raw(', $context[' . $key . ']);')
            ->raw('array_walk_recursive($context[' . $key . '], function($value, $key) {')
            ->raw('if ($value === ' . $requiredValue . ') {')
            ->raw('throw new Twig_Error_Runtime(\'required <em>' . $this->getAttribute('name') . '</em> property "\'.$key.\'" is not defined for "' . $this->getTemplateName() . '"\'); }')
            ->raw('});');
    }
}
