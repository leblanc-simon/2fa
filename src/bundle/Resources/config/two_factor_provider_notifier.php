<?php

declare(strict_types=1);

use Scheb\TwoFactorBundle\Notifier\SymfonyAuthCodeNotifier;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\DefaultTwoFactorFormRenderer;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Notifier\NotifierTwoFactorProvider;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Notifier\Generator\CodeGenerator;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Notifier\Generator\CodeGeneratorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('scheb_two_factor.security.notifier.symfony_auth_code_notifier', SymfonyAuthCodeNotifier::class)
            ->args([
                service('notifier'),
                '%scheb_two_factor.notifier.channel%',
                '%scheb_two_factor.notifier.importance%',
                inline_service('')
            ])

        ->set('scheb_two_factor.security.notifier.default_code_generator', CodeGenerator::class)
            ->lazy(true)
            ->args([
                service('scheb_two_factor.persister'),
                service('scheb_two_factor.security.notifier.symfony_auth_code_notifier'),
                '%scheb_two_factor.notifier.digits%',
            ])

        ->set('scheb_two_factor.security.notifier.default_form_renderer', DefaultTwoFactorFormRenderer::class)
            ->lazy(true)
            ->args([
                service('twig'),
                '%scheb_two_factor.notifier.template%',
            ])

        ->set('scheb_two_factor.security.notifier.provider', NotifierTwoFactorProvider::class)
            ->tag('scheb_two_factor.provider', ['alias' => 'notifier'])
            ->args([
                service('scheb_two_factor.security.notifier.code_generator'),
                service('scheb_two_factor.security.notifier.form_renderer'),
            ])

        ->alias(CodeGeneratorInterface::class, 'scheb_two_factor.security.notifier.code_generator')

        ->alias('scheb_two_factor.security.notifier.form_renderer', 'scheb_two_factor.security.notifier.default_form_renderer');
};
