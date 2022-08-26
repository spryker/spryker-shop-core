<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Yves\DateTimeConfiguratorPageExample\Business;

use Codeception\Test\Unit;
use SprykerShop\Yves\DateTimeConfiguratorPageExample\Plugin\Application\ConfiguratorSecurityHeaderExpanderPlugin;
use SprykerTest\Yves\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group DateTimeConfiguratorPageExample
 * @group Business
 * @group ConfiguratorSecurityHeaderExpanderPluginTest
 * Add your own group annotations below this line
 */
class ConfiguratorSecurityHeaderExpanderPluginTest extends Unit
{
    /**
     * @see {@link \Spryker\Yves\Application\ApplicationConfig::getSecurityHeaders()}
     *
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @var \SprykerTest\Yves\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleTester
     */
    protected DateTimeConfiguratorPageExampleTester $tester;

    /**
     * @return void
     */
    public function testExpandHeaderWithConfiguratorUrlAtTheEndOfTheLine(): void
    {
        // Arrange
        $configuratorSecurityHeaderExpanderPlugin = new ConfiguratorSecurityHeaderExpanderPlugin();

        $securityHeaders = [
            static::HEADER_CONTENT_SECURITY_POLICY => 'frame-ancestors \'self\'; sandbox allow-downloads allow-forms allow-modals allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation; base-uri \'self\'; form-action \'self\'',
        ];

        // Act
        $securityHeaders = $configuratorSecurityHeaderExpanderPlugin->expand($securityHeaders);

        // Assert
        $this->assertStringContainsString($this->tester->getConfiguratorUrl(), $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY]);
    }

    /**
     * @return void
     */
    public function testExpandHeaderWithConfiguratorUrlAtTheBeginningOfTheLine(): void
    {
        // Arrange
        $configuratorSecurityHeaderExpanderPlugin = new ConfiguratorSecurityHeaderExpanderPlugin();

        $securityHeaders = [
            static::HEADER_CONTENT_SECURITY_POLICY => 'form-action \'self\'; frame-ancestors \'self\'; sandbox allow-downloads allow-forms allow-modals allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation; base-uri \'self\'',
        ];

        // Act
        $securityHeaders = $configuratorSecurityHeaderExpanderPlugin->expand($securityHeaders);

        // Assert
        $this->assertStringContainsString($this->tester->getConfiguratorUrl(), $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY]);
    }

    /**
     * @return void
     */
    public function testExpandHeaderWithConfiguratorUrlInTheMiddleOfTheLine(): void
    {
        // Arrange
        $configuratorSecurityHeaderExpanderPlugin = new ConfiguratorSecurityHeaderExpanderPlugin();

        $securityHeaders = [
            static::HEADER_CONTENT_SECURITY_POLICY => 'frame-ancestors \'self\'; form-action \'self\'; sandbox allow-downloads allow-forms allow-modals allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation; base-uri \'self\'',
        ];

        // Act
        $securityHeaders = $configuratorSecurityHeaderExpanderPlugin->expand($securityHeaders);

        // Assert
        $this->assertStringContainsString($this->tester->getConfiguratorUrl(), $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY]);
    }

    /**
     * @return void
     */
    public function testDoNothingWithoutContentSecurityPolicyHeader(): void
    {
        // Arrange
        $configuratorSecurityHeaderExpanderPlugin = new ConfiguratorSecurityHeaderExpanderPlugin();

        $securityHeaders = [
            'FAKE_SECURITY_HEADER_1' => 'frame-ancestors \'self\'',
            'FAKE_SECURITY_HEADER_2' => 'frame-ancestors \'self\'',
        ];

        // Act
        $securityHeaders = $configuratorSecurityHeaderExpanderPlugin->expand($securityHeaders);

        // Assert
        $this->assertArrayNotHasKey(static::HEADER_CONTENT_SECURITY_POLICY, $securityHeaders);
    }

    /**
     * @return void
     */
    public function testDoNothingWithoutFormActionAttribute(): void
    {
        // Arrange
        $configuratorSecurityHeaderExpanderPlugin = new ConfiguratorSecurityHeaderExpanderPlugin();

        $securityHeaders = [
            static::HEADER_CONTENT_SECURITY_POLICY => 'frame-ancestors \'self\'',
        ];

        // Act
        $securityHeaders = $configuratorSecurityHeaderExpanderPlugin->expand($securityHeaders);

        // Assert
        $this->assertStringNotContainsString($this->tester->getConfiguratorUrl(), $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY]);
    }
}
