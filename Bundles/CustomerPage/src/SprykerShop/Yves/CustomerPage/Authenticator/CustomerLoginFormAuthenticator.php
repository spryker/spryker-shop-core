<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Authenticator;

use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\CustomerPage\Badge\MultiFactorAuthBadge;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class CustomerLoginFormAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FORM_NAME
     *
     * @var string
     */
    protected const PARAMETER_LOGIN_FORM = 'loginForm';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FIELD_REMEMBER_ME
     *
     * @var string
     */
    protected const PARAMETER_REMEMBER_ME = 'remember_me';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FIELD_EMAIL
     *
     * @var string
     */
    protected const PARAMETER_EMAIL = 'email';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\LoginForm::FIELD_PASSWORD
     *
     * @var string
     */
    protected const PARAMETER_PASSWORD = 'password';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGIN
     *
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @var string
     */
    protected const PARAMETER_OPTIONS = 'options';

    /**
     * @uses \Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants::CODE_BLOCKED
     *
     * @var int
     */
    protected const CODE_BLOCKED = 1;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge $rememberMeBadge
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface $authenticationSuccessHandler
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface $authenticationFailureHandler
     * @param \Spryker\Yves\Router\Router\ChainRouter $router
     * @param \SprykerShop\Yves\CustomerPage\Badge\MultiFactorAuthBadge $multiFactorAuthBadge
     */
    public function __construct(
        protected UserProviderInterface $userProvider,
        protected RememberMeBadge $rememberMeBadge,
        protected AuthenticationSuccessHandlerInterface $authenticationSuccessHandler,
        protected AuthenticationFailureHandlerInterface $authenticationFailureHandler,
        protected ChainRouter $router,
        protected MultiFactorAuthBadge $multiFactorAuthBadge
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Security\Http\Authenticator\Passport\Passport
     */
    public function authenticate(Request $request): Passport
    {
        $data = $request->request->all(static::PARAMETER_LOGIN_FORM);

        /** @var \SprykerShop\Yves\CustomerPage\Security\CustomerUserInterface $user */
        $user = $this->userProvider->loadUserByIdentifier($data[static::PARAMETER_EMAIL]);

        $badges = [];
        if (isset($data[static::PARAMETER_REMEMBER_ME])) {
            $badges[] = $this->rememberMeBadge->enable();
        }

        $badges[] = $this->multiFactorAuthBadge->enable(
            $user->getCustomerTransfer(),
            $request,
        );

        return new Passport(
            new UserBadge($data[static::PARAMETER_EMAIL], function () use ($user) {
                return $user;
            }),
            new PasswordCredentials($data[static::PARAMETER_PASSWORD]),
            $badges,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->request->has(static::PARAMETER_LOGIN_FORM);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $firewallName
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        return $this->authenticationSuccessHandler->onAuthenticationSuccess($request, $token);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->authenticationFailureHandler->onAuthenticationFailure($request, $exception);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse($this->router->generate(static::ROUTE_LOGIN));
    }

    /**
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\Passport $passport
     * @param string $firewallName
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        /** @var \SprykerShop\Yves\CustomerPage\Badge\MultiFactorAuthBadge $multiFactorAuthBadge */
        $multiFactorAuthBadge = $passport->getBadge(MultiFactorAuthBadge::class);

        if ($multiFactorAuthBadge->getIsRequired() === true || $multiFactorAuthBadge->getStatus() === static::CODE_BLOCKED) {
            return new NullToken();
        }

        return new PostAuthenticationToken(
            $passport->getUser(),
            $firewallName,
            $passport->getUser()->getRoles(),
        );
    }

    /**
     * @deprecated since Symfony 5.4, use {@link createToken()} instead.
     * Method exists only for PHPStan due to its fatal errors during analyzing files.
     *
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface $passport
     * @param string $firewallName
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface /** @phpstan-ignore-line */
    {
        return $this->createToken($passport, $firewallName);
    }
}
