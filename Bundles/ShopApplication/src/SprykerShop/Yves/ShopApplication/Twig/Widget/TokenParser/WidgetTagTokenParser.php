<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser;

use SprykerShop\Yves\ShopApplication\Twig\Widget\Node\WidgetTagTwigNode;
use Twig_Error_Syntax;
use Twig_Node;
use Twig_Node_Expression_Array;
use Twig_Node_Expression_Constant;
use Twig_Token;
use Twig_TokenParser;
use Twig_TokenStream;

class WidgetTagTokenParser extends Twig_TokenParser
{
    public const NODE_ARGS = 'args';

    public const NODE_USE = 'use';

    public const NODE_WITH = 'with';

    public const NODE_ELSEWIDGETS = 'elsewidgets';

    public const NODE_NOWIDGET = 'nowidget';

    public const NODE_WIDGET_EXPRESSION = 'widgetExpression';

    public const ATTRIBUTE_ONLY = 'only';

    public const ATTRIBUTE_PARENT_TEMPLATE_NAME = 'parentTemplateName';

    public const ATTRIBUTE_INDEX = 'index';

    public const ATTRIBUTE_ELSEWIDGET_CASE = 'elseWidgetCase';

    public const VARIABLE_WIDGET_TEMPLATE_PATH = '_widgetTemplatePath';

    public const VARIABLE_WIDGET = '_widget';

    protected const TOKEN_ELSEWIDGET = 'elsewidget';

    protected const TOKEN_NOWIDGET = 'nowidget';

    protected const TOKEN_ENDWIDGET = 'endwidget';

    protected const TOKEN_ONLY = 'only';

    protected const TOKEN_WITH = 'with';

    protected const TOKEN_USE = 'use';

    protected const TOKEN_ARGS = 'args';

    public function getTag(): string
    {
        return 'widget';
    }

    /**
     * @param \Twig_Token $token
     *
     * @return \SprykerShop\Yves\ShopApplication\Twig\Widget\Node\WidgetTagTwigNode
     */
    public function parse(Twig_Token $token): WidgetTagTwigNode
    {
        $stream = $this->parser->getStream();

        [$widgetName, $nodes] = $this->parseWidgetName($stream);
        [$nodes, $attributes] = $this->parseWidgetTagHead($nodes, $stream);
        [$nodes, $attributes] = $this->parseWidgetTagBody($nodes, $attributes, $stream, $token);
        [$nodes, $attributes] = $this->parseWidgetTagForks($nodes, $attributes, $stream, $token);

        return new WidgetTagTwigNode($widgetName, $nodes, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return array
     */
    protected function parseWidgetName(Twig_TokenStream $stream): array
    {
        $nodes = [];

        if ($stream->test(Twig_Token::STRING_TYPE)) {
            $widgetName = $stream->expect(Twig_Token::STRING_TYPE)->getValue();

            return [$widgetName, $nodes];
        }

        $nodes[static::NODE_WIDGET_EXPRESSION] = $this->parser->getExpressionParser()->parseExpression();

        return ['', $nodes];
    }

    /**
     * @param array $nodes
     * @param \Twig_TokenStream $stream
     *
     * @throws \Twig_Error_Syntax
     *
     * @return array
     */
    protected function parseWidgetTagHead(array $nodes, Twig_TokenStream $stream): array
    {
        $attributes = [];

        if ($args = $this->parseArgs($stream)) {
            if (isset($nodes[static::NODE_WIDGET_EXPRESSION])) {
                throw new Twig_Error_Syntax(
                    sprintf('Ambiguous use of "args", can be used only when widget\'s name defined as a string literal.'),
                    $stream->getCurrent()->getLine()
                );
            }
            $nodes[static::NODE_ARGS] = $args;
        }

        if ($use = $this->parseUse($stream)) {
            $nodes[static::NODE_USE] = $use;
        }

        if ($with = $this->parseWith($stream)) {
            $nodes[static::NODE_WITH] = $with;
        }

        $attributes[static::ATTRIBUTE_ONLY] = $this->parseOnly($stream);
        $attributes[static::ATTRIBUTE_ELSEWIDGET_CASE] = false;

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return [$nodes, $attributes];
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return \Twig_Node|null
     */
    protected function parseArgs(Twig_TokenStream $stream): ?Twig_Node
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, static::TOKEN_ARGS)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return \Twig_Node|null
     */
    protected function parseUse(Twig_TokenStream $stream): ?Twig_Node
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, static::TOKEN_USE)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return \Twig_Node|null
     */
    protected function parseWith(Twig_TokenStream $stream): ?Twig_Node
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, static::TOKEN_WITH)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return bool
     */
    protected function parseOnly(Twig_TokenStream $stream): bool
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, static::TOKEN_ONLY)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $nodes
     * @param array $attributes
     * @param \Twig_TokenStream $stream
     * @param \Twig_Token $token
     *
     * @return array
     */
    protected function parseWidgetTagBody(array $nodes, array $attributes, Twig_TokenStream $stream, Twig_Token $token): array
    {
        // fake extension from the (calculated) widget template
        $stream->injectTokens([
            new Twig_Token(Twig_Token::BLOCK_START_TYPE, '', $token->getLine()),
            new Twig_Token(Twig_Token::NAME_TYPE, 'extends', $token->getLine()),
            new Twig_Token(Twig_Token::NAME_TYPE, static::VARIABLE_WIDGET_TEMPLATE_PATH, $token->getLine()),
            new Twig_Token(Twig_Token::BLOCK_END_TYPE, '', $token->getLine()),
        ]);

        $body = $this->parser->parse($stream, [$this, 'decideIfFork']);

        $this->parser->embedTemplate($body);

        $attributes[static::ATTRIBUTE_PARENT_TEMPLATE_NAME] = $body->getTemplateName();
        $attributes[static::ATTRIBUTE_INDEX] = $body->getAttribute('index');

        return [$nodes, $attributes];
    }

    /**
     * @param array $nodes
     * @param array $attributes
     * @param \Twig_TokenStream $stream
     * @param \Twig_Token $token
     *
     * @throws \Twig_Error_Syntax
     *
     * @return array
     */
    protected function parseWidgetTagForks(array $nodes, array $attributes, Twig_TokenStream $stream, Twig_Token $token): array
    {
        $end = false;
        $elsewidgets = [];
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case static::TOKEN_ELSEWIDGET:
                    $index = count($elsewidgets) / 2;
                    $elsewidgets[] = new Twig_Node_Expression_Constant($index, $token->getLine());
                    $elsewidgets[] = $this->parseElsewidget($stream, $token);
                    break;

                case static::TOKEN_NOWIDGET:
                    $nodes[static::NODE_NOWIDGET] = $this->parseNowidget($stream);
                    break;

                case static::TOKEN_ENDWIDGET:
                    $end = true;
                    break;

                default:
                    throw new Twig_Error_Syntax(
                        sprintf(
                            'Unexpected end of template. Twig was looking for the following tags "nowidget", or "endwidget" to close the "widget" block started at line %d).',
                            $token->getLine()
                        ),
                        $stream->getCurrent()->getLine(),
                        $stream->getSourceContext()
                    );
            }
        }

        if ($elsewidgets) {
            $nodes[static::NODE_ELSEWIDGETS] = new Twig_Node_Expression_Array($elsewidgets, $token->getLine());
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return [$nodes, $attributes];
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return \Twig_Node
     */
    protected function parseNowidget(Twig_TokenStream $stream): Twig_Node
    {
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return $this->parser->subparse([$this, 'decideIfEnd']);
    }

    /**
     * @param \Twig_TokenStream $stream
     * @param \Twig_Token $token
     *
     * @return \Twig_Node
     */
    protected function parseElsewidget(Twig_TokenStream $stream, Twig_Token $token): Twig_Node
    {
        [$widgetName, $nodes] = $this->parseWidgetName($stream);
        [$nodes, $attributes] = $this->parseWidgetTagHead($nodes, $stream);
        [$nodes, $attributes] = $this->parseWidgetTagBody($nodes, $attributes, $stream, $token);

        $attributes[static::ATTRIBUTE_ELSEWIDGET_CASE] = true;

        return new WidgetTagTwigNode($widgetName, $nodes, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function decideIfFork(Twig_Token $token): bool
    {
        return $token->test([
            static::TOKEN_ELSEWIDGET,
            static::TOKEN_NOWIDGET,
            static::TOKEN_ENDWIDGET,
        ]);
    }

    /**
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function decideIfEnd(Twig_Token $token): bool
    {
        return $token->test([static::TOKEN_ENDWIDGET]);
    }
}
