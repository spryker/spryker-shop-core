<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 1/3/18
 * Time: 11:21
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

interface CustomerReorderWidgetToMessengerClientInterface
{
    /**
     * @return void
     */
    public function processFlashMessagesFromLastZedRequest(): void;
}
