<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Plugin\Twig;

use InvalidArgumentException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiFactory getFactory()
 * @method \SprykerShop\Yves\ShopUi\ShopUiConfig getConfig()
 */
class GeneratePathTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var array<string, string>
     */
    protected static array $urlCache = [];

    /**
     * @var string
     */
    protected const TWIG_FUNCTION_NAME_GENERATE_PATH = 'generatePath';

    /**
     * @var string
     */
    protected const SERVICE_ROUTERS = 'routers';

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const PARAMETER_QUERY = 'query';

    /**
     * @var string
     */
    protected const PARAMETER_PATH = 'path';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFunctions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->createGeneratePath());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function createGeneratePath(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_GENERATE_PATH,
            function (?string $url): ?string {
                if ($url === null) {
                    return null;
                }
                if (isset(static::$urlCache[$url])) {
                    return static::$urlCache[$url];
                }

                $parsedUrl = parse_url($url);
                /** @var string $path */
                $path = $parsedUrl[static::PARAMETER_PATH] ?? '';
                $query = isset($parsedUrl[static::PARAMETER_QUERY])
                    ? sprintf('?%s', $parsedUrl[static::PARAMETER_QUERY])
                    : '';

                /** @var \Symfony\Component\Routing\RouterInterface $router */
                $router = $this->getContainer()->get(static::SERVICE_ROUTERS);

                $context = $router->getContext();
                $context->setQueryString('');
                $router->setContext($context);

                try {
                    $newPath = $router->generate($path);

                    static::$urlCache[$url] = sprintf('%s%s', $newPath, $query);

                    return static::$urlCache[$url];
                } catch (InvalidArgumentException $exception) {
                    if ($this->getConfig()->isStoreRoutingEnabled() === false) {
                        return $url;
                    }
                    $store = $this->getContainer()->get(static::SERVICE_STORE);

                    return str_starts_with($path, '/' . $store)
                        ? sprintf('%s%s', $path, $query)
                        : sprintf('/%s%s%s', $store, $path, $query);
                }
            },
        );
    }
}
