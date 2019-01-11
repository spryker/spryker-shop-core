<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\FileOutputter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileOutputter implements FileOutputterInterface
{
    protected const FILE_TEMPLATE_NAME = 'quick-order-template';

    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplatePluginInterface[]
     */
    protected $quickOrderFileTemplatePlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplatePluginInterface[] $quickOrderFileTemplatePlugins
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
    public function outputFile(string $fileType)
    {

        foreach ($this->quickOrderFileTemplatePlugins as $fileTemplatePlugin) {
            if ($fileTemplatePlugin->isApplicable($fileType)) {
                $fileName =  static::FILE_TEMPLATE_NAME . '.' . $fileTemplatePlugin->getFileExtension();
                // Return a response with a specific content
                $response = new Response($fileTemplatePlugin->generateTemplate());
                $response->headers->set('Content-Type', $fileTemplatePlugin->getTemplateMimeType());
                // Create the disposition of the file
                $disposition = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $fileName
                );

                // Set the content disposition
                $response->headers->set('Content-Disposition', $disposition);

                // Dispatch request
                return $response;
            }
        }
    }
}
