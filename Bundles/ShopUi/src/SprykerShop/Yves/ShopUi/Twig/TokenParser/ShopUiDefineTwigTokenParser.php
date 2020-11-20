<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\TokenParser;

use SprykerShop\Yves\ShopUi\ShopUiConfig;
use SprykerShop\Yves\ShopUi\Twig\Node\ShopUiDefineTwigNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class ShopUiDefineTwigTokenParser extends AbstractTokenParser
{
    /**
     * @var \SprykerShop\Yves\ShopUi\ShopUiConfig
     */
    protected $shopUiConfig;

    /**
     * @param \SprykerShop\Yves\ShopUi\ShopUiConfig $shopUiConfig
     */
    public function __construct(ShopUiConfig $shopUiConfig)
    {
        $this->shopUiConfig = $shopUiConfig;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'define';
    }

    /**
     * @param \Twig\Token $token
     *
     * @return \SprykerShop\Yves\ShopUi\Twig\Node\ShopUiDefineTwigNode
     */
    public function parse(Token $token): ShopUiDefineTwigNode
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stream->expect(Token::OPERATOR_TYPE, '=');
        $value = $parser->getExpressionParser()->parseExpression();
        $line = $token->getLine();
        $tag = $this->getTag();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new ShopUiDefineTwigNode($this->shopUiConfig, $name, $value, $line, $tag);
    }
}
