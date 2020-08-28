<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Provider;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * @method \Spryker\Client\Agent\AgentClientInterface getClient()
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getConfig()
 */
class AccessDeniedHandler extends AbstractPlugin implements AccessDeniedHandlerInterface
{
    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @param string $targetUrl
     */
    public function __construct(string $targetUrl)
    {
        $this->targetUrl = $targetUrl;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AccessDeniedException $accessDeniedException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?RedirectResponse
    {
        return $this->getFactory()->createRedirectResponse($this->targetUrl);
    }
}
