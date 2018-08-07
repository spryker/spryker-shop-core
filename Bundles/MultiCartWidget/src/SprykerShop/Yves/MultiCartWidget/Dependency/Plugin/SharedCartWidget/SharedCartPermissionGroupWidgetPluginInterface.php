<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 8/7/18
 * Time: 7:52 AM
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;


use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartPermissionGroupWidgetPluginInterface
{
    public const NAME = 'SharedCartPermissionGroupWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
