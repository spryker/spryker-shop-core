<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Renderer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileDownloadRenderer implements FileRendererInterface
{
    protected const FILE_TEMPLATE_NAME = 'quick-order-template';
    protected const RESPONSE_CODE_NOT_FOUND = 404;

    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface[]
     */
    protected $quickOrderFileTemplatePlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface[] $quickOrderFileTemplatePlugins
     */
    public function __construct(array $quickOrderFileTemplatePlugins)
    {
        $this->quickOrderFileTemplatePlugins = $quickOrderFileTemplatePlugins;
    }

    /**
     * @param string $fileType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(string $fileType): Response
    {
        foreach ($this->quickOrderFileTemplatePlugins as $fileTemplatePlugin) {
            if ($fileTemplatePlugin->isApplicable($fileType)) {
                $fileName = static::FILE_TEMPLATE_NAME . '.' . $fileTemplatePlugin->getFileExtension();
                $response = new Response($fileTemplatePlugin->generateTemplate());
                $disposition = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $fileName
                );
                $response->headers->set('Content-Disposition', $disposition);
                $response->headers->set('Content-Type', $fileTemplatePlugin->getTemplateMimeType());

                return $response;
            }
        }

        return new Response('', static::RESPONSE_CODE_NOT_FOUND);
    }
}
