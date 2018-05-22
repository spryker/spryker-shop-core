<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManager\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method \SprykerShop\Yves\FileManager\FileManagerFactory getFactory()
 */
class DownloadController extends AbstractController
{
    const CONTENT_DISPOSITION = 'Content-Disposition';
    const CONTENT_TYPE = 'Content-Type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $fileName
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, string $fileName)
    {
        $fileManagerDataTransfer = $this->getFactory()->getFileManagerService()->read($fileName);

        return $this->createResponse($fileManagerDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $response = new Response($fileManagerDataTransfer->getContent());
        $fileName = $fileManagerDataTransfer->getFile()->getFileName();
        $contentType = $fileManagerDataTransfer->getFileInfo()->getType();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
