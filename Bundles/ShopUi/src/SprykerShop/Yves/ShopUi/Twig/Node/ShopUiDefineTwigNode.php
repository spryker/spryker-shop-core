<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Node;

use SprykerShop\Yves\ShopUi\ShopUiConfig;
use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

class ShopUiDefineTwigNode extends Node
{
    /**
     * @var string
     */
    public const REQUIRED_VALUE = '___REQUIRED___';

    /**
     * @var \SprykerShop\Yves\ShopUi\ShopUiConfig
     */
    protected $shopUiConfig;

    /**
     * @param \SprykerShop\Yves\ShopUi\ShopUiConfig $shopUiConfig
     * @param string $name
     * @param \Twig\Node\Expression\AbstractExpression $value
     * @param int $line
     * @param string|null $tag
     */
    public function __construct(
        ShopUiConfig $shopUiConfig,
        string $name,
        AbstractExpression $value,
        int $line,
        ?string $tag = null
    ) {
        parent::__construct(['value' => $value], ['name' => $name], $line, $tag);

        $this->shopUiConfig = $shopUiConfig;
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler): void
    {
        $key = "'" . $this->getAttribute('name') . "'";

        $compiler->addDebugInfo($this);

        $this->addDefaultValueSetter($compiler, $key);
        $this->addValueReplacer($compiler, $key);
        $this->addRequiredValueCheck($compiler, $key);
    }

    /**
     * @param \Twig\Compiler $compiler
     * @param string $key
     *
     * @return \Twig\Compiler
     */
    protected function addDefaultValueSetter(Compiler $compiler, string $key): Compiler
    {
        $compiler->raw('if (!array_key_exists(' . $key . ', $context)) {')
            ->raw('$context[' . $key . '] = [];')
            ->raw('}');

        return $compiler;
    }

    /**
     * @param \Twig\Compiler $compiler
     * @param string $key
     *
     * @return \Twig\Compiler
     */
    protected function addValueReplacer(Compiler $compiler, string $key): Compiler
    {
        $compiler->raw('$context[' . $key . '] = array_replace_recursive(')
            ->subcompile($this->getNode('value'))
            ->raw(', $context[' . $key . ']);');

        return $compiler;
    }

    /**
     * @param \Twig\Compiler $compiler
     * @param string $key
     *
     * @return \Twig\Compiler
     */
    protected function addRequiredValueCheck(Compiler $compiler, string $key): Compiler
    {
        if (!$this->shopUiConfig->isDevelopmentMode()) {
            return $compiler;
        }

        $requiredValue = "'" . static::REQUIRED_VALUE . "'";

        $compiler->raw('array_walk_recursive($context[' . $key . '], function($value, $key) {')
            ->raw('if ($value === ' . $requiredValue . ') {')
            ->raw('throw new RuntimeError(\'required <em>' . $this->getAttribute('name') . '</em> property "\'.$key.\'" is not defined for "' . $this->getTemplateName() . ':' . $this->getTemplateLine() . '"\'); }')
            ->raw('});');

        return $compiler;
    }
}
