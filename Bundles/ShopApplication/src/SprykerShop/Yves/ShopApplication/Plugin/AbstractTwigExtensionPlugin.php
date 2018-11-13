<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Extension\ExtensionInterface;
use Twig_Environment;

/**
 * @method \Spryker\Yves\Kernel\AbstractFactory getFactory()
 */
abstract class AbstractTwigExtensionPlugin extends AbstractPlugin implements ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @api
     *
     * @param \Twig_Environment $environment The current Twig_Environment instance
     *
     * @return void
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @api
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return [];
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @api
     *
     * @return \Twig_NodeVisitorInterface[] An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return [];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @api
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @api
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return [];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @api
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @api
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return [];
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @api
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [];
    }
}
