<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Twig\Tag;

use SprykerShop\Yves\ShopUi\Twig\Tag\ShopUiDefineTwigNode;
use Twig_TokenParser;
use Twig_Token;

class ShopUiDefineTwigTokenParser extends Twig_TokenParser
{
    public function getTag()
    {
        return 'define';
    }

    public function parse(Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
        $value = $parser->getExpressionParser()->parseExpression();
        $line = $token->getLine();
        $tag = $this->getTag();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new ShopUiDefineTwigNode($name, $value, $line, $tag);
    }
}
