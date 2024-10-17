<?php

declare(strict_types=1);

namespace Scheb\TwoFactorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

/**
 * Determine the default mailer to use.
 *
 * @final
 */
class NotifierCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('scheb_two_factor.security.notifier.provider')) {
            // Notifier authentication is not enabled
            return;
        }

        if ($container->hasAlias('scheb_two_factor.security.notifier.auth_code_notification')) {
            // Custom AuthCodeNotification
            return;
        }

        if (!$container->hasDefinition('notifier')) {
            throw new LogicException('Could not determine default notification service to use. Please install symfony/notifier or create your own notification and configure it under "scheb_two_factor.notifier.notification.');
        }

        $container->setAlias('scheb_two_factor.security.notifier.auth_code_notification', 'scheb_two_factor.security.notifier.symfony_auth_code_notification');
    }
}
