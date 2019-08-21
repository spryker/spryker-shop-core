<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Twig\Node;

use SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin;
use SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser;
use Twig\Compiler;
use Twig\Node\Node;

class ShopCmsSlotNode extends Node
{
    /**
     * @var string
     */
    protected $cmsSlotKey;

    /**
     * @param string $cmsSlotKey
     * @param array $nodes
     * @param array $attributes
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(
        string $cmsSlotKey,
        array $nodes,
        array $attributes,
        int $lineno = 0,
        ?string $tag = null
    ) {
        $this->cmsSlotKey = $cmsSlotKey;

        parent::__construct($nodes, $attributes, $lineno, $tag);
    }

    /**
     * @param \Twig\Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler): void
    {
        $compiler->raw(
            sprintf(
                'echo $this->env->getExtension(\'%s\')->getSlotContent(\'%s\', ',
                ShopCmsSlotTwigPlugin::class,
                $this->cmsSlotKey
            )
        );
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_WITH);
        $compiler->raw(', ');
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_REQUIRED);
        $compiler->raw(', ');
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_AUTOFILLED);
        $compiler->raw(');');
    }

    /**
     * @param \Twig\Compiler $compiler
     * @param string $nodeName
     *
     * @return void
     */
    protected function compileArrayNode(Compiler $compiler, string $nodeName): void
    {
        if (!$this->hasNode($nodeName)) {
            $compiler->raw('[]');

            return;
        }

        $compiler->subcompile($this->getNode($nodeName));
    }
}
