<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 1/3/18
 * Time: 11:19
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;


class CustomerReorderWidgetToMessengerClientBridge implements CustomerReorderWidgetToMessengerClientInterface
{
    /**
     * @var \Spryker\Client\Messenger\MessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Client\Messenger\MessengerClientInterface $messengerClient
     */
    public function __construct($messengerClient)
    {
        $this->messengerClient = $messengerClient;
    }

    /**
     * @return void
     */
    public function processFlashMessagesFromLastZedRequest(): void
    {
        $this->messengerClient->processFlashMessagesFromLastZedRequest();
    }
}
