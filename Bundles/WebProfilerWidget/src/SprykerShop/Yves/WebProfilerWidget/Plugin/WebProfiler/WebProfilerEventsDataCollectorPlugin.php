<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget\Plugin\WebProfiler;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\DataCollector\EventDataCollector;

class WebProfilerEventsDataCollectorPlugin implements WebProfilerDataCollectorPluginInterface
{
    protected const NAME = 'events';
    protected const TEMPLATE = '@WebProfiler/Collector/events.html.twig';

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
     * - Adds a EventDataCollector which collects information about the triggered events.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface
     */
    public function getDataCollector(ContainerInterface $container): DataCollectorInterface
    {
        return new EventDataCollector($container->get(EventDispatcherApplicationPlugin::SERVICE_DISPATCHER));
    }
}
