<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/3/18
 * Time: 16:58
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;


use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class CustomerReorderWidgetToZedRequestClientBridge implements CustomerReorderWidgetToZedRequestClientInterface
{
    /**
     * @var ZedRequestClientInterface
     */
    private $zedRequestClient;

    /**
     * @param ZedRequestClientInterface $zedRequestClient
     */
    public function __construct($zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest()
    {
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
    }
}
