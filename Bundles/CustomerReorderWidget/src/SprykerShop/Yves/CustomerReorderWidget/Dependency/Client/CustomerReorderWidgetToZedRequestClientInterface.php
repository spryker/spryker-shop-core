<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/3/18
 * Time: 17:00
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

interface CustomerReorderWidgetToZedRequestClientInterface
{
    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();
}
