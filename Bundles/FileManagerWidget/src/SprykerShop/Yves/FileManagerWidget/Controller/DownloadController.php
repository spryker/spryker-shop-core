<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Controller;

use Generated\Shared\Transfer\FileStorageDataTransfer;
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
    /**
     * @var string
     */
    protected const PARAM_ID_FILE = 'id-file';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
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
        $fileStorageDataTransfer = $this->getFactory()
            ->getFileManagerStorageClient()
            ->findFileById(
                $request->query->getInt(static::PARAM_ID_FILE),
                $this->getLocale(),
            );

        if ($fileStorageDataTransfer === null) {
            throw new NotFoundHttpException();
        }

        return $this->createDownloadResponse($fileStorageDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageDataTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function createDownloadResponse(FileStorageDataTransfer $fileStorageDataTransfer): StreamedResponse
    {
        $storageFileName = $fileStorageDataTransfer->getStorageFileNameOrFail();

        $fileStream = $this->getFactory()
            ->getFileManagerService()
            ->readStream($storageFileName);

        $response = new StreamedResponse(function () use ($fileStream) {
            echo stream_get_contents($fileStream);
        });

        $storageFileName = basename($storageFileName);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $storageFileName);
        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);

        $contentType = $fileStorageDataTransfer->getTypeOrFail();
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }
}
