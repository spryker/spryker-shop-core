<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Plugin\CmsContentWidget;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\FileManagerWidget\FileManagerWidgetFactory getFactory()
 */
class FileManagerWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
{
    /**
     * @var \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected $widgetConfiguration;

    /**
     * @param \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface $widgetConfiguration
     */
    public function __construct(CmsContentWidgetConfigurationProviderInterface $widgetConfiguration)
    {
        $this->widgetConfiguration = $widgetConfiguration;
    }

    /**
     * @return callable
     */
    public function getContentWidgetFunction()
    {
        return [$this, 'contentWidgetFunction'];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param string $idFile
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, string $idFile, ?string $templateIdentifier = null): string
    {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $idFile)
        );
    }

    /**
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath(?string $templateIdentifier = null): string
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param array $context
     * @param string $idFile
     *
     * @return array
     */
    protected function getContent(array $context, string $idFile): array
    {
        $fileTransfer = $this->getFileManagerStorageClient()->findFileById((int)$idFile, $this->getLocale());

        return ['file' => $fileTransfer];
    }

    /**
     * @return \SprykerShop\Yves\FileManagerWidget\Dependency\Client\FileManagerWidgetToFileManagerStorageClientInterface
     */
    protected function getFileManagerStorageClient()
    {
        return $this->getFactory()->getFileManagerStorageClient();
    }
}
