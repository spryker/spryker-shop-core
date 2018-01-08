<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

use Spryker\Shared\Kernel\Communication\Application;

interface TwigRendererInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $application
     * @param array $parameters
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function render(Application $application, array $parameters = []);
}
