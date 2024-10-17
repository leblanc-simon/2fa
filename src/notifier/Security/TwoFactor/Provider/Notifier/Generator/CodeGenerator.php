<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Notifier\Generator;

use Scheb\TwoFactorBundle\Notifier\AuthCodeNotifierInterface;
use Scheb\TwoFactorBundle\Model\Notifier\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\PersisterInterface;
use function random_int;

/**
 * @final
 */
class CodeGenerator implements CodeGeneratorInterface
{
    public function __construct(
        private readonly PersisterInterface $persister,
        private readonly AuthCodeNotifierInterface $notifier,
        private readonly int $digits,
    ) {
    }

    public function generateAndSend(TwoFactorInterface $user): void
    {
        $min = 10 ** ($this->digits - 1);
        $max = 10 ** $this->digits - 1;
        $code = $this->generateCode($min, $max);
        $user->setNotifierAuthCode((string) $code);
        $this->persister->persist($user);
        $this->notifier->sendAuthCode($user);
    }

    public function reSend(TwoFactorInterface $user): void
    {
        $this->notifier->sendAuthCode($user);
    }

    protected function generateCode(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
