<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use SprykerShop\Yves\WebProfilerWidgetExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class WebProfilerAjaxDataCollectorPlugin implements WebProfilerDataCollectorPluginInterface
{
    protected const NAME = 'ajax';
    protected const TEMPLATE = '@WebProfiler/Collector/ajax.html.twig';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return static::TEMPLATE;
    }

    /**
     * {@inheritDoc}
     * - Adds a AjaxDataCollector which collects information about asynchronous requests.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return new AjaxDataCollector();
    }
}
