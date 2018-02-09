<?php

namespace SprykerShop\Yves\FileManager\Controller;

use Generated\Shared\Transfer\FileManagerReadResponseTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\FileManager\FileManagerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method FileManagerFactory getFactory()
 */
class DownloadController extends AbstractController
{

    const CONTENT_DISPOSITION = 'Content-Disposition';
    const CONTENT_TYPE = 'Content-Type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function indexAction(Request $request, string $fileName)
    {
        $fileManagerReadTransfer = $this->getFactory()->getFileManagerService()->read($fileName);

        return $this->createResponse($fileManagerReadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerReadResponseTransfer $fileManagerReadResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(FileManagerReadResponseTransfer $fileManagerReadResponseTransfer)
    {
        $response = new Response($fileManagerReadResponseTransfer->getContent());
        $fileName = $fileManagerReadResponseTransfer->getFile()->getFileName();
        $contentType = $fileManagerReadResponseTransfer->getFileInfo()->getType();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, $contentType);

        return $response;
    }

}