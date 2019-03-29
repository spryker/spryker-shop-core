<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\Node;

use SprykerShop\Yves\ShopApplication\Plugin\Twig\WidgetTagTwigPlugin;
use SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser\WidgetTagTwigTokenParser;
use Twig\Compiler;
use Twig\Node\Node;

class WidgetTagNode extends Node
{
    /**
     * @var string
     */
    protected $widgetName;

    /**
     * @param string $widgetName
     * @param array $nodes
     * @param array $attributes
     * @param int $lineNumber
     * @param string|null $tag
     */
    public function __construct(string $widgetName, array $nodes = [], array $attributes = [], int $lineNumber = 0, ?string $tag = null)
    {
        parent::__construct($nodes, $attributes, $lineNumber, $tag);

        $this->widgetName = $widgetName;
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler): void
    {
        if (!$this->getAttribute(WidgetTagTwigTokenParser::ATTRIBUTE_ELSEWIDGET_CASE)) {
            $compiler
                ->addDebugInfo($this)
                ->write('if (');
        }

        $this->addOpenWidgetContext($compiler);
        $compiler->raw(")) {\n")->indent(1);

        $this->addLoadTemplate($compiler);

        $this->addCloseWidgetContext($compiler);

        $compiler->outdent(1)->write('}');

        $this->compileElsewidgets($compiler);
        $this->compileNowidget($compiler);
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addOpenWidgetContext(Compiler $compiler): void
    {
        $compiler->raw(sprintf('$widget = $this->env->getExtension(\'%s\')->openWidgetContext(', WidgetTagTwigPlugin::class));

        if ($this->hasNode(WidgetTagTwigTokenParser::NODE_WIDGET_EXPRESSION)) {
            $compiler->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_WIDGET_EXPRESSION));
        } else {
            $compiler->string($this->widgetName);
        }

        if ($this->hasNode(WidgetTagTwigTokenParser::NODE_ARGS)) {
            $compiler
                ->raw(', ')
                ->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_ARGS));
        }
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addLoadTemplate(Compiler $compiler): void
    {
        $compiler
            ->write('$this->loadTemplate(')
            ->repr($this->getAttribute(WidgetTagTwigTokenParser::ATTRIBUTE_PARENT_TEMPLATE_NAME))
            ->raw(', ')
            ->repr($this->getTemplateName())
            ->raw(', ')
            ->repr($this->getTemplateLine())
            ->raw(', ')
            ->repr($this->attributes['index'])
            ->raw(')->display(');

        $this->addTemplateArguments($compiler);

        $compiler->raw(");\n");
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addTemplateArguments(Compiler $compiler): void
    {
        if (!$this->hasNode(WidgetTagTwigTokenParser::NODE_WITH)) {
            if ($this->getAttribute(WidgetTagTwigTokenParser::ATTRIBUTE_ONLY)) {
                // template path only
                $this->addDisplayMetaArguments($compiler);

                return;
            }

            // context only + template path
            $compiler->raw('array_merge($context, ');
            $this->addDisplayMetaArguments($compiler);
            $compiler->raw(')');

            return;
        }

        if ($this->getAttribute(WidgetTagTwigTokenParser::ATTRIBUTE_ONLY)) {
            // arguments + template path
            $compiler->raw('array_merge(');
            $this->addDisplayMetaArguments($compiler);
            $compiler
                ->raw(', ')
                ->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_WITH))
                ->raw(')');

            return;
        }

        // context + template path + arguments
        $compiler->raw('array_merge($context, ');
        $this->addDisplayMetaArguments($compiler);
        $compiler
            ->raw(', ')
            ->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_WITH))
            ->raw(')');
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addDisplayMetaArguments(Compiler $compiler): void
    {
        $compiler->raw('array(');
        $this->addWidgetMetaArgument($compiler);
        $compiler->raw(', ');
        $this->addTemplatePathMetaArgument($compiler);
        $compiler->raw(')');
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addCloseWidgetContext(Compiler $compiler): void
    {
        $compiler->write(sprintf("\$this->env->getExtension('%s')->closeWidgetContext();\n", WidgetTagTwigPlugin::class));
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addWidgetMetaArgument(Compiler $compiler): void
    {
        $compiler->raw(sprintf('"%s" => $widget', WidgetTagTwigTokenParser::VARIABLE_WIDGET));
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function addTemplatePathMetaArgument(Compiler $compiler): void
    {
        $compiler->raw(sprintf(
            '"%s" => $this->env->getExtension(\'%s\')->getTemplatePath($widget',
            WidgetTagTwigTokenParser::VARIABLE_WIDGET_TEMPLATE_PATH,
            WidgetTagTwigPlugin::class
        ));

        if ($this->hasNode(WidgetTagTwigTokenParser::NODE_USE)) {
            $compiler
                ->raw(', ')
                ->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_USE));
        }

        $compiler->raw(')');
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function compileElsewidgets(Compiler $compiler): void
    {
        if (!$this->hasNode(WidgetTagTwigTokenParser::NODE_ELSEWIDGETS)) {
            return;
        }

        foreach ($this->getNode(WidgetTagTwigTokenParser::NODE_ELSEWIDGETS) as $widgetTagTwigNode) {
            if (!$widgetTagTwigNode instanceof static) {
                continue;
            }

            $compiler
                ->raw(' elseif (')
                ->subcompile($widgetTagTwigNode);
        }
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    protected function compileNowidget(Compiler $compiler): void
    {
        if (!$this->hasNode(WidgetTagTwigTokenParser::NODE_NOWIDGET)) {
            return;
        }

        $compiler
            ->raw(" else {\n")
            ->indent(1)
            ->subcompile($this->getNode(WidgetTagTwigTokenParser::NODE_NOWIDGET))
            ->outdent(1)
            ->write("}\n");
    }
}
