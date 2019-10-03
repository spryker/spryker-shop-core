<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Twig\Node;

use Generated\Shared\Transfer\CmsSlotContextTransfer;
use SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin;
use SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser;
use Twig\Compiler as TwigCompiler;
use Twig\Node\Node as TwigNode;

class ShopCmsSlotNode extends TwigNode
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
    public function compile(TwigCompiler $compiler): void
    {
        $compiler->addDebugInfo($this)->raw(
            sprintf(
                'echo $this->env->getExtension(\'%s\')->getSlotContent((new %s())->setCmsSlotKey(\'%s\')',
                ShopCmsSlotTwigPlugin::class,
                CmsSlotContextTransfer::class,
                $this->cmsSlotKey
            )
        );
        $compiler->raw('->setProvidedData(');
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_WITH);
        $compiler->raw(')->setRequiredKeys(');
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_REQUIRED);
        $compiler->raw(')->setAutoFilledKeys(');
        $this->compileArrayNode($compiler, ShopCmsSlotTokenParser::NODE_AUTOFILLED);
        $compiler->raw('));');
    }

    /**
     * @param \Twig\Compiler $compiler
     * @param string $nodeName
     *
     * @return void
     */
    protected function compileArrayNode(TwigCompiler $compiler, string $nodeName): void
    {
        if (!$this->hasNode($nodeName)) {
            $compiler->raw('[]');

            return;
        }

        $compiler->subcompile($this->getNode($nodeName));
    }
}
