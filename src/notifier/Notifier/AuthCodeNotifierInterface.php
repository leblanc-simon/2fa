<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Notifier;

use Scheb\TwoFactorBundle\Model\Notifier\TwoFactorInterface;

interface AuthCodeNotifierInterface
{
    /**
     * Send the auth code to the user via email / sms.
     */
    public function sendAuthCode(TwoFactorInterface $user): void;
}
