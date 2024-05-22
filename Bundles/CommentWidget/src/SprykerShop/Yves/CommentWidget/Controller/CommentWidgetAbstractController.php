<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentWidgetAbstractController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = 'name';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_TOKEN = '_token';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MESSAGE_FORM_CSRF_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ADD_COMMENT_FORM = 'add-comment-form';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_UPDATE_COMMENT_FORM = 'update-comment-form';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH = 'comment.validation.error.invalid_message_length';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function handleResponseMessages(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        if ($commentThreadResponseTransfer->getIsSuccessful()) {
            return;
        }

        foreach ($commentThreadResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function executeCommentThreadAfterSuccessfulOperation(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return;
        }

        $this->getFactory()
            ->createCommentOperation()
            ->executeCommentThreadAfterOperationPlugins($commentThreadResponseTransfer->getCommentThread());
    }

    /**
     * @param string $tokenId
     * @param string $value
     *
     * @return bool
     */
    protected function validateCsrfToken(string $tokenId, string $value): bool
    {
        $csrfToken = new CsrfToken($tokenId, $value);

        return $this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransferFromRequest(Request $request): CommentTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->fromArray($request->request->all(), true)
            ->setCustomer($customerTransfer);

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getCommentThreadResponse(CommentThreadResponseTransfer $commentThreadResponseTransfer): JsonResponse
    {
        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessages($commentThreadResponseTransfer->getMessages()->getArrayCopy());

            return $this->getMessagesJsonResponse();
        }

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            static::KEY_CONTENT => $this->getTwig()->render(
                '@CommentWidget/views/comment-thread-async/comment-thread-async.twig',
                $this->getViewData($commentThreadResponseTransfer->getCommentThreadOrFail()),
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return array<string, mixed>
     */
    protected function getViewData(CommentThreadTransfer $commentThreadTransfer): array
    {
        $commentThreadTransfer = $this->getFactory()
            ->createCommentThreadExpander()
            ->expandCommentsWithPlainTags($commentThreadTransfer);

        return [
            'customer' => $this->getFactory()->getCustomerClient()->getCustomer(),
            'ownerId' => $commentThreadTransfer->getOwnerId(),
            'ownerType' => $commentThreadTransfer->getOwnerType(),
            'taggedComments' => $this->getFactory()->createCommentThreadPreparer()->prepareTaggedComments($commentThreadTransfer),
            'availableCommentTags' => $this->getFactory()->getCommentClient()->getAvailableCommentTags(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getMessagesJsonResponse(): JsonResponse
    {
        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
        ]);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function addErrorMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $idToken
     *
     * @return bool
     */
    protected function validateCsrfTokenInRequest(Request $request, string $idToken): bool
    {
        $tokenValue = (string)$request->get(static::REQUEST_PARAMETER_TOKEN);

        if (!$this->validateCsrfToken($idToken, $tokenValue)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_ERROR_MESSAGE_FORM_CSRF_ERROR);

            return false;
        }

        return true;
    }
}
