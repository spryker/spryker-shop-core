<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\ControllerResolver;

use InvalidArgumentException;
use Spryker\Service\Container\ContainerInterface;

/**
 * @deprecated Use `spryker/router` instead.
 */
class CallbackControllerResolver implements CallbackControllerResolverInterface
{
    protected const SERVICE_PATTERN = "/[A-Za-z0-9\._\-]+:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/";

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $name
     *
     * @return bool
     */
    public function isValid($name): bool
    {
        return is_string($name) && preg_match(static::SERVICE_PATTERN, $name);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function convertCallback(string $name): array
    {
        [$service, $method] = explode(':', $name, 2);

        if (!$this->container->has($service)) {
            throw new InvalidArgumentException(sprintf('Service "%s" does not exist.', $service));
        }

        return [$this->container->get($service), $method];
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function resolveCallback(string $name): array
    {
        if (!$this->isValid($name)) {
            throw new InvalidArgumentException(sprintf('Name of service "%s" is not valid.', $name));
        }

        return $this->convertCallback($name);
    }
}
