<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Yves\DateTimeConfiguratorPageExample;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class DateTimeConfiguratorPageExampleTester extends Actor
{
    use _generated\DateTimeConfiguratorPageExampleTesterActions;

    /**
     * @return string
     */
    public function getConfiguratorUrl(): string
    {
        return sprintf(
            '%s://%s',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_PORT') === '443' ? 'https' : 'http',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST') ?: '',
        );
    }
}
