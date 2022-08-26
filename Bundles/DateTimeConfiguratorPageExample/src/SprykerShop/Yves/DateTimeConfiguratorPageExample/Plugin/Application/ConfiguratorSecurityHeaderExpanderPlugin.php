<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DateTimeConfiguratorPageExample\Plugin\Application;

use Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface;

class ConfiguratorSecurityHeaderExpanderPlugin implements SecurityHeaderExpanderPluginInterface
{
    /**
     * @see {@link \Spryker\Yves\Application\ApplicationConfig::getSecurityHeaders()}
     *
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @var string
     */
    protected const ATTRIBUTE_FORM_ACTION = 'form-action';

    /**
     * {@inheritDoc}
     * - Adds configurator url to `Content-Security-Policy` header.
     * - Enables redirect to configurator page with `form-action` protection.
     *
     * @api
     *
     * @param array<string, string> $securityHeaders
     *
     * @return array<string, string>
     */
    public function expand(array $securityHeaders): array
    {
        $contentSecurityPolicyHeader = $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY] ?? null;

        if (!$contentSecurityPolicyHeader) {
            return $securityHeaders;
        }

        $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY] = str_replace(
            static::ATTRIBUTE_FORM_ACTION,
            sprintf('%s %s', static::ATTRIBUTE_FORM_ACTION, $this->createConfiguratorUrl()),
            $contentSecurityPolicyHeader,
        );

        return $securityHeaders;
    }

    /**
     * @return string
     */
    protected function createConfiguratorUrl(): string
    {
        return sprintf(
            '%s://%s',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_PORT') === '443' ? 'https' : 'http',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST') ?: '',
        );
    }
}
