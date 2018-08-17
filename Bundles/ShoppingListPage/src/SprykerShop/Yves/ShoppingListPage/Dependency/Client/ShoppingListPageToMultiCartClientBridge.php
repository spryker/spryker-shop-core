<?php
/**
 * Created by PhpStorm.
 * User: ruslan.ivanov
 * Date: 8/17/18
 * Time: 3:24 PM
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class ShoppingListPageToMultiCartClientBridge implements ShoppingListPageToMultiCartClientBridgeInterface
{
    /**
     * @var \Spryker\Client\MultiCart\MultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Client\MultiCart\MultiCartClientInterface $multiCartClient
     */
    public function __construct($multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @param int $idQuote
     * @return null|QuoteTransfer
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        return $this->multiCartClient->findQuoteById($idQuote);
    }
}