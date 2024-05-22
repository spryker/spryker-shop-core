<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentTagAsyncController extends CommentWidgetAbstractController
{
    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_COMMENT_TAG_FORM = 'comment-tag-form';

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(string $uuid, Request $request): JsonResponse
    {
        if (!$this->validateCsrfTokenInRequest($request, static::CSRF_TOKEN_ID_COMMENT_TAG_FORM)) {
            return $this->getMessagesJsonResponse();
        }

        $name = (string)$request->request->get(static::PARAMETER_NAME) ?: null;

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setComment((new CommentTransfer())->setUuid($uuid))
            ->setName($name);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->addCommentTag($commentTagRequestTransfer);

        return $this->getCommentThreadResponse($commentThreadResponseTransfer);
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(string $uuid, Request $request): JsonResponse
    {
        if (!$this->validateCsrfTokenInRequest($request, static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM)) {
            return $this->getMessagesJsonResponse();
        }

        $name = (string)$request->query->get(static::PARAMETER_NAME) ?: null;

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setComment((new CommentTransfer())->setUuid($uuid))
            ->setName($name);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeCommentTag($commentTagRequestTransfer);

        if ($commentThreadResponseTransfer->getIsSuccessful()) {
            $commentThreadResponseTransfer = $this->removeTagsFromCommentThreadResponse(
                $commentThreadResponseTransfer,
                $name,
            );
        }

        return $this->getCommentThreadResponse($commentThreadResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     * @param string $removedTagName
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function removeTagsFromCommentThreadResponse(
        CommentThreadResponseTransfer $commentThreadResponseTransfer,
        string $removedTagName
    ): CommentThreadResponseTransfer {
        foreach ($commentThreadResponseTransfer->getCommentThread()->getComments() as $commentTransfer) {
            $commentTagTransfers = new ArrayObject();

            foreach ($commentTransfer->getCommentTags() as $commentTagTransfer) {
                if ($commentTagTransfer->getName() !== $removedTagName) {
                    $commentTagTransfers->append($commentTagTransfer);
                }
            }

            $commentTransfer->setCommentTags($commentTagTransfers);
        }

        return $commentThreadResponseTransfer;
    }
}
