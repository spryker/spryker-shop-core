<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 */
class OrderAmendmentItemLinkWidget extends AbstractWidget
{
    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTERS = 'routers';

    /**
     * @var string
     */
    protected const PARAMETER_URL = 'url';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = 'name';

    /**
     * @param string $url
     * @param string $name
     */
    public function __construct(string $url, string $name)
    {
        $this->addUrlParameter($url);
        $this->addNameParameter($name);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderAmendmentItemLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderAmendmentWidget/views/order-amendment-item-link/order-amendment-item-link.twig';
    }

    /**
     * @param string $url
     *
     * @return void
     */
    protected function addUrlParameter(string $url): void
    {
        $url = $this->generateUrl($url);
        $this->addParameter(static::PARAMETER_URL, $url);
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function addNameParameter(string $name): void
    {
        $this->addParameter(static::PARAMETER_NAME, $name);
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    protected function generateUrl(string $url): ?string
    {
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);

        try {
            $url = $routers->generate($url);
        } catch (RouteNotFoundException $exception) {
            return null;
        }

        return $url;
    }
}
