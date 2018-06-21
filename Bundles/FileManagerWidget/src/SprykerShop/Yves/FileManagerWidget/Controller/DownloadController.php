<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\ReadFileTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\FileManagerWidget\FileManagerWidgetFactory getFactory()
 */
class DownloadController extends AbstractController
{
    protected const PARAM_ID_FILE = 'id-file';
    protected const CONTENT_TYPE = 'Content-Type';
    protected const CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request): StreamedResponse
    {
        $readFileTransfer = $this->createReadFileTransfer($request);

        $fileManagerDataTransfer = $this->getFactory()
            ->getFileManagerClient()
            ->readFile($readFileTransfer);

        if ($fileManagerDataTransfer->getFileInfo() === null) {
            throw new NotFoundHttpException();
        }

        return $this->createDownloadResponse($fileManagerDataTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ReadFileTransfer
     */
    protected function createReadFileTransfer(Request $request): ReadFileTransfer
    {
        $transfer = new ReadFileTransfer();

        $transfer
            ->setIdFile(
                $request->query->getInt(self::PARAM_ID_FILE)
            );

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function createDownloadResponse(FileManagerDataTransfer $fileManagerDataTransfer): StreamedResponse
    {
        $storageFileName = $fileManagerDataTransfer->getFileInfo()
            ->getStorageFileName();

        $fileStream = $this->getFactory()
            ->getFileManagerService()
            ->readStream($storageFileName);

        $response = new StreamedResponse(function () use ($fileStream) {
            echo stream_get_contents($fileStream);
        });

        $storageFileName = basename($storageFileName);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $storageFileName);
        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);

        $contentType = $fileManagerDataTransfer->getFileInfo()->getType();
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
