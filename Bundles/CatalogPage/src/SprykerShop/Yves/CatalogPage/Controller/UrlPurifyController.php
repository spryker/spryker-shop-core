<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class UrlPurifyController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $requestParams = $request->request->all();
        $url = stripslashes($request->get('url'));
        unset($requestParams['url']);
        $requestParams = $this->getFactory()
            ->createRequestAttributesPurifier()
            ->purifyRequestAttributes($requestParams);

        $purifiedUrl = $this->getRouter()->generate($url, $requestParams);

        return $this->jsonResponse([
            'url' => $request->getSchemeAndHttpHost() . $purifiedUrl,
        ]);
    }
}
