<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Notifier;

use Scheb\TwoFactorBundle\Model\Notifier\TwoFactorInterface;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notification\SmsNotificationInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * @final
 */
class SymfonyAuthCodeNotifier implements AuthCodeNotifierInterface
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly string|null $channel,
        private readonly string|null $importance,
        private readonly EmailNotificationInterface|SmsNotificationInterface|null $notification,
    ) {
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $authCode = $user->getNotifierAuthCode();
        if (null === $authCode) {
            return;
        }

        $notification = $this->notification;
        if (null === $notification) {
            $notification = new Notification('Authentication Code');
        }

        if (null !== $this->channel) {
            $notification->channels([$this->channel]);
        }

        if (null !== $this->importance) {
            $notification->importance($this->importance);
        }

        $notification->content($authCode);

        $this->notifier->send(
            $notification,
            new Recipient($user->getEmailAuthRecipient() ?? '', $user->getPhoneAuthRecipient() ?? '')
        );
    }
}
