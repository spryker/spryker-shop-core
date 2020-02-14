<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser;

use SprykerShop\Yves\ShopApplication\Twig\Widget\Node\WidgetTagNode;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TokenStream;

class WidgetTagTwigTokenParser extends AbstractTokenParser
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

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'widget';
    }

    /**
     * @param \Twig\Token $token
     *
     * @return \Twig\Node\Node
     */
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        [$widgetName, $nodes] = $this->parseWidgetName($stream);
        [$nodes, $attributes] = $this->parseWidgetTagHead($nodes, $stream);
        [$nodes, $attributes] = $this->parseWidgetTagBody($nodes, $attributes, $stream, $token);
        [$nodes, $attributes] = $this->parseWidgetTagForks($nodes, $attributes, $stream, $token);

        return new WidgetTagNode($widgetName, $nodes, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return array
     */
    protected function parseWidgetName(TokenStream $stream): array
    {
        $nodes = [];

        if ($stream->test(Token::STRING_TYPE)) {
            $widgetName = $stream->expect(Token::STRING_TYPE)->getValue();

            return [$widgetName, $nodes];
        }

        $nodes[static::NODE_WIDGET_EXPRESSION] = $this->parser->getExpressionParser()->parseExpression();

        return ['', $nodes];
    }

    /**
     * @param array $nodes
     * @param \Twig\TokenStream $stream
     *
     * @throws \Twig\Error\SyntaxError
     *
     * @return array
     */
    protected function parseWidgetTagHead(array $nodes, TokenStream $stream): array
    {
        $attributes = [];

        if ($args = $this->parseArgs($stream)) {
            if (isset($nodes[static::NODE_WIDGET_EXPRESSION])) {
                throw new SyntaxError(
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

        $stream->expect(Token::BLOCK_END_TYPE);

        return [$nodes, $attributes];
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return \Twig\Node\Node|null
     */
    protected function parseArgs(TokenStream $stream): ?Node
    {
        if ($stream->nextIf(Token::NAME_TYPE, static::TOKEN_ARGS)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return \Twig\Node\Node|null
     */
    protected function parseUse(TokenStream $stream): ?Node
    {
        if ($stream->nextIf(Token::NAME_TYPE, static::TOKEN_USE)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return \Twig\Node\Node|null
     */
    protected function parseWith(TokenStream $stream): ?Node
    {
        if ($stream->nextIf(Token::NAME_TYPE, static::TOKEN_WITH)) {
            return $this->parser->getExpressionParser()->parseExpression();
        }

        return null;
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return bool
     */
    protected function parseOnly(TokenStream $stream): bool
    {
        if ($stream->nextIf(Token::NAME_TYPE, static::TOKEN_ONLY)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $nodes
     * @param array $attributes
     * @param \Twig\TokenStream $stream
     * @param \Twig\Token $token
     *
     * @return array
     */
    protected function parseWidgetTagBody(array $nodes, array $attributes, TokenStream $stream, Token $token): array
    {
        // fake extension from the (calculated) widget template
        $stream->injectTokens([
            new Token(Token::BLOCK_START_TYPE, '', $token->getLine()),
            new Token(Token::NAME_TYPE, 'extends', $token->getLine()),
            new Token(Token::NAME_TYPE, static::VARIABLE_WIDGET_TEMPLATE_PATH, $token->getLine()),
            new Token(Token::BLOCK_END_TYPE, '', $token->getLine()),
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
     * @param \Twig\TokenStream $stream
     * @param \Twig\Token $token
     *
     * @throws \Twig\Error\SyntaxError
     *
     * @return array
     */
    protected function parseWidgetTagForks(array $nodes, array $attributes, TokenStream $stream, Token $token): array
    {
        $end = false;
        $elsewidgets = [];
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case static::TOKEN_ELSEWIDGET:
                    $index = count($elsewidgets) / 2;
                    $elsewidgets[] = new ConstantExpression($index, $token->getLine());
                    $elsewidgets[] = $this->parseElsewidget($stream, $token);

                    break;
                case static::TOKEN_NOWIDGET:
                    $nodes[static::NODE_NOWIDGET] = $this->parseNowidget($stream);

                    break;
                case static::TOKEN_ENDWIDGET:
                    $end = true;

                    break;
                default:
                    throw new SyntaxError(
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
            $nodes[static::NODE_ELSEWIDGETS] = new ArrayExpression($elsewidgets, $token->getLine());
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return [$nodes, $attributes];
    }

    /**
     * @param \Twig\TokenStream $stream
     *
     * @return \Twig\Node\Node
     */
    protected function parseNowidget(TokenStream $stream): Node
    {
        $stream->expect(Token::BLOCK_END_TYPE);

        return $this->parser->subparse([$this, 'decideIfEnd']);
    }

    /**
     * @param \Twig\TokenStream $stream
     * @param \Twig\Token $token
     *
     * @return \Twig\Node\Node
     */
    protected function parseElsewidget(TokenStream $stream, Token $token): Node
    {
        [$widgetName, $nodes] = $this->parseWidgetName($stream);
        [$nodes, $attributes] = $this->parseWidgetTagHead($nodes, $stream);
        [$nodes, $attributes] = $this->parseWidgetTagBody($nodes, $attributes, $stream, $token);

        $attributes[static::ATTRIBUTE_ELSEWIDGET_CASE] = true;

        return new WidgetTagNode($widgetName, $nodes, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * @param \Twig\Token $token
     *
     * @return bool
     */
    public function decideIfFork(Token $token): bool
    {
        return $token->test([
            static::TOKEN_ELSEWIDGET,
            static::TOKEN_NOWIDGET,
            static::TOKEN_ENDWIDGET,
        ]);
    }

    /**
     * @param \Twig\Token $token
     *
     * @return bool
     */
    public function decideIfEnd(Token $token): bool
    {
        return $token->test([static::TOKEN_ENDWIDGET]);
    }
}
