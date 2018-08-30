<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\ShopApplication\Twig\Widget;

use SprykerShop\Yves\ShopApplication\Twig\Widget\Node\WidgetTagTwigNode;
use SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser\WidgetTagTokenParser;
use Twig_Node_Expression_Array;
use Twig_Node_Expression_Constant;
use Twig_Node_Text;
use Twig_Test_NodeTestCase;

class WidgetTagTest extends Twig_Test_NodeTestCase
{
    /**
     * @return array
     */
    public function getTests()
    {
        return [
            'simple widget tag' => $this->getSimpleWidgetTagTestCase(),
            'widget tag with arguments' => $this->getWidgetTagWithArgumentsTestCase(),
            'widget tag with view' => $this->getWidgetTagWithViewTestCase(),
            'widget tag with variables' => $this->getWidgetTagWithVariablesTestCase(),
            'widget tag with variables only' => $this->getWidgetTagWithVariablesOnlyTestCase(),
            'widget tag without variables only' => $this->getWidgetTagWithoutVariablesOnlyTestCase(),
            'widget tag with nowidget' => $this->getWidgetTagWithNowidgetTestCase(),
        ];
    }

    /**
     * {% widget 'CurrencyWidgetPlugin' %}{%endwidget}
     *
     * @return array
     */
    protected function getSimpleWidgetTagTestCase()
    {
        $node = new WidgetTagTwigNode('foo', [], $this->getAttributes(), 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(\$context, array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath())));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'CurrencyWidgetPlugin' args ['foo', 123] %}{%endwidget}
     *
     * @return array
     */
    protected function getWidgetTagWithArgumentsTestCase()
    {
        $nodes = [
            WidgetTagTokenParser::NODE_ARGS => new Twig_Node_Expression_Array([
                new Twig_Node_Expression_Constant(0, 1),
                new Twig_Node_Expression_Constant('foo', 1),
                new Twig_Node_Expression_Constant(1, 1),
                new Twig_Node_Expression_Constant(123, 1),
            ], 1),
        ];

        $node = new WidgetTagTwigNode('foo', $nodes, $this->getAttributes(), 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo", array(0 => "foo", 1 => 123))) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(\$context, array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath())));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'CurrencyWidgetPlugin' use 'test-view.twig' %}{%endwidget}
     *
     * @return array
     */
    protected function getWidgetTagWithViewTestCase()
    {
        $nodes = [
            WidgetTagTokenParser::NODE_USE => new Twig_Node_Expression_Constant('custom-view.twig', 1),
        ];

        $node = new WidgetTagTwigNode('foo', $nodes, $this->getAttributes(), 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(\$context, array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath("custom-view.twig"))));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'nameOfTheWidget' with {
     *     foo: 'bar',
     *     baz: {
     *         a: 'b'
     *     }
     * } %}{%endwidget}
     *
     * @return array
     */
    protected function getWidgetTagWithVariablesTestCase()
    {
        $nodes = [
            WidgetTagTokenParser::NODE_WITH => new Twig_Node_Expression_Array([
                new Twig_Node_Expression_Constant('foo', 1),
                new Twig_Node_Expression_Constant('bar', 1),
                new Twig_Node_Expression_Constant('baz', 1),
                new Twig_Node_Expression_Array([
                    new Twig_Node_Expression_Constant('a', 1),
                    new Twig_Node_Expression_Constant('b', 1),
                ], 1),
            ], 1),
        ];

        $node = new WidgetTagTwigNode('foo', $nodes, $this->getAttributes(), 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(\$context, array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath()), array("foo" => "bar", "baz" => array("a" => "b"))));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'nameOfTheWidget' with {
     *     foo: 'bar',
     *     baz: {
     *         a: 'b'
     *     }
     * } only %}{%endwidget}
     *
     * @return array
     */
    protected function getWidgetTagWithVariablesOnlyTestCase()
    {
        $nodes = [
            WidgetTagTokenParser::NODE_WITH => new Twig_Node_Expression_Array([
                new Twig_Node_Expression_Constant('foo', 1),
                new Twig_Node_Expression_Constant('bar', 1),
                new Twig_Node_Expression_Constant('baz', 1),
                new Twig_Node_Expression_Array([
                    new Twig_Node_Expression_Constant('a', 1),
                    new Twig_Node_Expression_Constant('b', 1),
                ], 1),
            ], 1),
        ];

        $attributes = $this->getAttributes([
            WidgetTagTokenParser::ATTRIBUTE_ONLY => true,
        ]);

        $node = new WidgetTagTwigNode('foo', $nodes, $attributes, 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath()), array("foo" => "bar", "baz" => array("a" => "b"))));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'nameOfTheWidget' only %}{%endwidget}
     *
     * @return array
     */
    protected function getWidgetTagWithoutVariablesOnlyTestCase()
    {
        $nodes = [];

        $attributes = $this->getAttributes([
            WidgetTagTokenParser::ATTRIBUTE_ONLY => true,
        ]);

        $node = new WidgetTagTwigNode('foo', $nodes, $attributes, 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath()));
    \$context['app']['widget_tag_service']->closeWidgetContext();
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * {% widget 'nameOfTheWidget' %}
     * {% nowidget %}
     *     widget not available
     * {% endwidget %}
     *
     * @return array
     */
    protected function getWidgetTagWithNowidgetTestCase()
    {
        $nodes = [
            WidgetTagTokenParser::NODE_NOWIDGET => new Twig_Node_Text('content of nowidget', 1),
        ];

        $node = new WidgetTagTwigNode('foo', $nodes, $this->getAttributes(), 1, 'widget');

        $expectedCode = <<<EOF
// line 1
if (\$context['app']['widget_tag_service']->openWidgetContext(\$this->getEnvironment(), "foo")) {
    \$this->loadTemplate("parent-template-name.twig", null, 1, 123)->display(array_merge(\$context, array("_widget" => \$context['app']['widget_tag_service']->getCurrentWidget(), "_widgetTemplatePath" => \$context['app']['widget_tag_service']->getTemplatePath())));
    \$context['app']['widget_tag_service']->closeWidgetContext();
} else {
    echo "content of nowidget";
}
EOF;

        return [$node, $expectedCode];
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributes(array $attributes = [])
    {
        return array_merge([
            WidgetTagTokenParser::ATTRIBUTE_PARENT_TEMPLATE_NAME => 'parent-template-name.twig',
            WidgetTagTokenParser::ATTRIBUTE_INDEX => 123,
            WidgetTagTokenParser::ATTRIBUTE_ONLY => false,
        ], $attributes);
    }
}
