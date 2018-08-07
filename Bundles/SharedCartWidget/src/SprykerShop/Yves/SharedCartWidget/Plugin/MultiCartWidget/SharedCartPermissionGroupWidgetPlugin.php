<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 8/7/18
 * Time: 8:00 AM
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;


use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartPermissionGroupWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartPermissionGroupWidgetPlugin extends AbstractWidgetPlugin implements SharedCartPermissionGroupWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('accessType', $this->getAccessType($quoteTransfer));
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @return string
     */
    protected function getAccessType(QuoteTransfer $quoteTransfer): string
    {
        return $this->getFactory()
            ->getSharedCartClient()
            ->calculatePermission($quoteTransfer);
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@SharedCartWidget/views/shared-cart-permission/shared-cart-permission.twig';
    }
}
