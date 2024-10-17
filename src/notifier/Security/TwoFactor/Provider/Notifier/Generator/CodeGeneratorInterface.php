<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Notifier\Generator;

use Scheb\TwoFactorBundle\Model\Notifier\TwoFactorInterface;

interface CodeGeneratorInterface
{
    /**
     * Generate a new authentication code an send it to the user.
     */
    public function generateAndSend(TwoFactorInterface $user): void;
}
