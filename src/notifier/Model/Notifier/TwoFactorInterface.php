<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Model\Notifier;

interface TwoFactorInterface
{
    /**
     * Return true if the user should do two-factor authentication.
     */
    public function isNotifierAuthEnabled(): bool;

    /**
     * Return user email address (require only if your app use email notifier).
     */
    public function getEmailAuthRecipient(): string|null;

    /**
     * Return user phone number (require only if your app use sms notifier).
     */
    public function getPhoneAuthRecipient(): string|null;

    /**
     * Return the authentication code.
     */
    public function getNotifierAuthCode(): string|null;

    /**
     * Set the authentication code.
     */
    public function setNotifierAuthCode(string $authCode): void;
}
