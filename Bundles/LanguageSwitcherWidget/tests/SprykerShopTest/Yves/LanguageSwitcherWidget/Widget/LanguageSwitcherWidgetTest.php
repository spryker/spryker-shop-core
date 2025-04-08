<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\LanguageSwitcherWidget\Widget;

use Codeception\Test\Unit;
use SprykerShopTest\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group LanguageSwitcherWidget
 * @group Widget
 * @group LanguageSwitcherWidgetTest
 * Add your own group annotations below this line
 */
class LanguageSwitcherWidgetTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetTester
     */
    protected LanguageSwitcherWidgetTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setRequestAttributes();
        $this->tester->setupContainer();
    }

    /**
     * @return void
     */
    public function testGetParametersReturnsUrlsWithCorrectQueryParameters(): void
    {
        // Arrange
        $widget = $this->tester->createLanguageSwitcherWidget(
            $this->tester::HOME_PATH,
            $this->tester::TEST_QUERY_STRING,
            $this->tester->createRequestUri($this->tester::HOME_PATH, $this->tester::TEST_QUERY_STRING),
        );

        // Act
        $parameters = $widget->getParameters();

        // Assert
        $this->assertArrayHasKey('languages', $parameters);
        $this->assertArrayHasKey('currentLanguage', $parameters);

        foreach ($parameters['languages'] as $url) {
            $this->assertSame(sprintf('%s?%s', $this->tester::TEST_ROUTE, $this->tester::TEST_QUERY_STRING), $url);
        }
    }

    /**
     * @return void
     */
    public function testGetParametersReturnsUrlsWithoutQueryParameters(): void
    {
        // Arrange
        $widget = $this->tester->createLanguageSwitcherWidget(
            $this->tester::ROOT_PATH,
            null,
            $this->tester->createRequestUri($this->tester::ROOT_PATH),
        );

        // Act
        $parameters = $widget->getParameters();

        // Assert
        $this->assertArrayHasKey('languages', $parameters);
        $this->assertArrayHasKey('currentLanguage', $parameters);

        foreach ($parameters['languages'] as $url) {
            $this->assertSame($this->tester::TEST_ROUTE, $url);
        }
    }

    /**
     * @return void
     */
    public function testGetParametersReturnsUrlsWithCombinedQueryParameters(): void
    {
        // Arrange
        $this->tester->request->attributes->set('_route', $this->tester::TEST_ROUTE_WITH_PARAMS);
        $widget = $this->tester->createLanguageSwitcherWidget(
            $this->tester::HOME_PATH,
            $this->tester::TEST_QUERY_STRING_2,
            $this->tester->createRequestUri($this->tester::HOME_PATH, $this->tester::TEST_QUERY_STRING_2),
        );

        // Act
        $parameters = $widget->getParameters();

        // Assert
        $this->assertArrayHasKey('languages', $parameters);
        $this->assertArrayHasKey('currentLanguage', $parameters);

        foreach ($parameters['languages'] as $url) {
            $this->assertSame(sprintf('%s&%s', $this->tester::TEST_ROUTE_WITH_PARAMS, $this->tester::TEST_QUERY_STRING_2), $url);
        }
    }
}
