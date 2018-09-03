<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
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
     * Handles an access denied failure.
     *
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     *
     * @return RedirectResponse|null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?RedirectResponse
    {
        return $this->getFactory()->createRedirectResponse($this->targetUrl);
    }
}
