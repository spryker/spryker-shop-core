<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\FileManagerWidget\Plugin\Router\FileManagerWidgetRouteProviderPlugin` instead.
 */
class FileManagerWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_FILES_DOWNLOAD = 'files/download';

    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @param bool|null $sslEnabled
     */
    public function __construct(?bool $sslEnabled = null)
    {
        parent::__construct($sslEnabled);

        $this->allowedLocalesPattern = $this->getAllowedLocalesPattern();
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController('/{files}/download', static::ROUTE_FILES_DOWNLOAD, 'FileManagerWidget', 'Download')
            ->assert('files', $this->allowedLocalesPattern . 'files|files')
            ->value('files', 'files');
    }
}
