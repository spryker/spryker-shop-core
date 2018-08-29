<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser;

use SprykerShop\Yves\ShopApplication\Twig\Widget\Node\WidgetTagTwigNode;
use Twig_Error_Syntax;
use Twig_Token;
use Twig_TokenParser;
use Twig_TokenStream;

class WidgetTagTokenParser extends Twig_TokenParser
{
    public const NODE_ARGS = 'args';

    public const NODE_USE = 'use';

    public const NODE_WITH = 'with';

    public const NODE_NOWIDGET = 'nowidget';

    public const ATTRIBUTE_ONLY = 'only';

    public const ATTRIBUTE_PARENT_TEMPLATE_NAME = 'parentTemplateName';

    public const ATTRIBUTE_INDEX = 'index';

    public const VARIABLE_WIDGET_TEMPLATE_PATH = '_widgetTemplatePath';

    public const VARIABLE_WIDGET = '_widget';

    /**
     * @var string
     */
    protected $widgetName;

    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return string
     */
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
        $this->resetParserAttributes();

        $stream = $this->parser->getStream();

        $this->parseWidgetTagHead($stream);
        $this->parseWidgetTagBody($stream, $token);

        return new WidgetTagTwigNode($this->widgetName, $this->nodes, $this->attributes, $token->getLine(), $this->getTag());
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseWidgetTagHead(Twig_TokenStream $stream): void
    {
        $this->parseWidgetName($stream);
        $this->parseArgs($stream);
        $this->parseUse($stream);
        $this->parseWith($stream);
        $this->parseOnly($stream);

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
    }

    /**
     * @param \Twig_TokenStream $stream
     * @param \Twig_Token $token
     *
     * @throws \Twig_Error_Syntax
     *
     * @return void
     */
    protected function parseWidgetTagBody(Twig_TokenStream $stream, Twig_Token $token): void
    {
        $this->parseParentTemplate($stream, $token);

        $nowidget = null;
        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'nowidget':
                    $this->parseNowidget($stream);
                    break;

                case 'endwidget':
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

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
    }

    /**
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function decideIfFork(Twig_Token $token): bool
    {
        return $token->test(['nowidget', 'endwidget']);
    }

    /**
     * @param \Twig_Token $token
     *
     * @return bool
     */
    public function decideIfEnd(Twig_Token $token): bool
    {
        return $token->test(['endwidget']);
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseWidgetName(Twig_TokenStream $stream): void
    {
        $this->widgetName = $stream->expect(Twig_Token::STRING_TYPE)->getValue();
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseArgs(Twig_TokenStream $stream): void
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'args')) {
            $this->nodes[static::NODE_ARGS] = $this->parser->getExpressionParser()->parseExpression();
        }
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseUse(Twig_TokenStream $stream): void
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'use')) {
            $this->nodes[self::NODE_USE] = $this->parser->getExpressionParser()->parseExpression();
        }
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseWith(Twig_TokenStream $stream): void
    {
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'with')) {
            $this->nodes[self::NODE_WITH] = $this->parser->getExpressionParser()->parseExpression();
        }
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseOnly(Twig_TokenStream $stream): void
    {
        $this->attributes['only'] = false;
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'only')) {
            $this->attributes[self::ATTRIBUTE_ONLY] = true;
        }
    }

    /**
     * @param \Twig_TokenStream $stream
     * @param \Twig_Token $token
     *
     * @return void
     */
    protected function parseParentTemplate(Twig_TokenStream $stream, Twig_Token $token): void
    {
        // fake extension from the (calculated) widget template
        $stream->injectTokens([
            new Twig_Token(Twig_Token::BLOCK_START_TYPE, '', $token->getLine()),
            new Twig_Token(Twig_Token::NAME_TYPE, 'extends', $token->getLine()),
            new Twig_Token(Twig_Token::NAME_TYPE, self::VARIABLE_WIDGET_TEMPLATE_PATH, $token->getLine()),
            new Twig_Token(Twig_Token::BLOCK_END_TYPE, '', $token->getLine()),
        ]);

        $body = $this->parser->parse($stream, [$this, 'decideIfFork']);

        $this->parser->embedTemplate($body);

        $this->attributes[self::ATTRIBUTE_PARENT_TEMPLATE_NAME] = $body->getTemplateName();
        $this->attributes[self::ATTRIBUTE_INDEX] = $body->getAttribute('index');
    }

    /**
     * @param \Twig_TokenStream $stream
     *
     * @return void
     */
    protected function parseNowidget(Twig_TokenStream $stream): void
    {
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $this->nodes[self::NODE_NOWIDGET] = $this->parser->subparse([$this, 'decideIfEnd']);
    }

    /**
     * @return void
     */
    protected function resetParserAttributes(): void
    {
        $this->widgetName = null;
        $this->nodes = [];
        $this->attributes = [];
    }
}
