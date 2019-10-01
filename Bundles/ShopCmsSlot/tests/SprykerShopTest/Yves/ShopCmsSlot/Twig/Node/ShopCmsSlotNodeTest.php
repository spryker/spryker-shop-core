<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopCmsSlot\Twig\Node;

use SprykerShop\Yves\ShopCmsSlot\Twig\Node\ShopCmsSlotNode;
use SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Test\NodeTestCase;

class ShopCmsSlotNodeTest extends NodeTestCase
{
    /**
     * @return array
     */
    public function getTests(): array
    {
        return [
            'cms slot tag' => $this->getCmsSlotTagTestCase(),
            'cms slot tag with data' => $this->getCmsSlotTagWithDataTestCase(),
            'cms slot tag with required' => $this->getCmsSlotTagWithRequiredTestCase(),
            'cms slot tag with autofilled data' => $this->getCmsSlotTagWithAutofilledDataTestCase(),
            'cms slot tag with data and required' => $this->getCmsSlotTagWithDataAndRequiredTestCase(),
            'cms slot tag with autofilled data and required' => $this->getCmsSlotTagWithAutofilledDataAndRequiredTestCase(),
            'cms slot tag with data and autofilled data' => $this->getCmsSlotTagWithDataAndAutofilledDataTestCase(),
            'cms slot tag with all data' => $this->getCmsSlotTagWithAllDataTestCase(),
        ];
    }

    /**
     * {% cms_slot "cms-slot-key" %}
     *
     * @return array
     */
    protected function getCmsSlotTagTestCase(): array
    {
        $node = new ShopCmsSlotNode('cms-slot-key', [], [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData([])->setRequiredKeys([])->setAutoFilledKeys([]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" with {idProduct: 123} %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithDataTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_WITH => new ArrayExpression([
                new ConstantExpression('idProduct', 1),
                new ConstantExpression(123, 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData(["idProduct" => 123])->setRequiredKeys([])->setAutoFilledKeys([]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" required ['idProduct'] %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithRequiredTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_REQUIRED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('idProduct', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData([])->setRequiredKeys([0 => "idProduct"])->setAutoFilledKeys([]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" autofilled ['user'] %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithAutofilledDataTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_AUTOFILLED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('user', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData([])->setRequiredKeys([])->setAutoFilledKeys([0 => "user"]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" required ['idProduct'] with {idProduct: 123} %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithDataAndRequiredTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_WITH => new ArrayExpression([
                new ConstantExpression('idProduct', 1),
                new ConstantExpression(123, 1),
            ], 1),
            ShopCmsSlotTokenParser::NODE_REQUIRED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('idProduct', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData(["idProduct" => 123])->setRequiredKeys([0 => "idProduct"])->setAutoFilledKeys([]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" autofilled ['user'] required ['idProduct'] %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithAutofilledDataAndRequiredTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_REQUIRED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('idProduct', 1),
            ], 1),
            ShopCmsSlotTokenParser::NODE_AUTOFILLED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('user', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData([])->setRequiredKeys([0 => "idProduct"])->setAutoFilledKeys([0 => "user"]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" autofilled ['user'] with {idProduct: 123} %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithDataAndAutofilledDataTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_WITH => new ArrayExpression([
                new ConstantExpression('idProduct', 1),
                new ConstantExpression(123, 1),
            ], 1),
            ShopCmsSlotTokenParser::NODE_AUTOFILLED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('user', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData(["idProduct" => 123])->setRequiredKeys([])->setAutoFilledKeys([0 => "user"]));
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% cms_slot "cms-slot-key" autofilled ['user'] required ['idProduct'] with {idProduct: 123} %}
     *
     * @return array
     */
    protected function getCmsSlotTagWithAllDataTestCase(): array
    {
        $nodes = [
            ShopCmsSlotTokenParser::NODE_WITH => new ArrayExpression([
                new ConstantExpression('idProduct', 1),
                new ConstantExpression(123, 1),
            ], 1),
            ShopCmsSlotTokenParser::NODE_REQUIRED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('idProduct', 1),
            ], 1),
            ShopCmsSlotTokenParser::NODE_AUTOFILLED => new ArrayExpression([
                new ConstantExpression(0, 1),
                new ConstantExpression('user', 1),
            ], 1),
        ];

        $node = new ShopCmsSlotNode('cms-slot-key', $nodes, [], 1, 'cms_slot');

        $expectedCode = <<<EOF
// line 1
echo \$this->env->getExtension('SprykerShop\Yves\ShopCmsSlot\Plugin\Twig\ShopCmsSlotTwigPlugin')->getSlotContent((new Generated\Shared\Transfer\CmsSlotContextTransfer())->setCmsSlotKey('cms-slot-key')->setProvidedData(["idProduct" => 123])->setRequiredKeys([0 => "idProduct"])->setAutoFilledKeys([0 => "user"]));
EOF;

        return [$node, $expectedCode];
    }
}
