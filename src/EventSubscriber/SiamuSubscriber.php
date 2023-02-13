<?php

namespace App\EventSubscriber;

use App\Service\Siamu;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SiamuSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly Siamu                 $siamu,
        private readonly Security              $security,
        private readonly UrlGeneratorInterface $router
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($this->security->isGranted('ROLE_DEVELOPER') || $_ENV['APP_ENV'] === 'dev') {
            // Si l'utilisateur est un dev, on lui laisse l'accés au site
            // Même chose si on est en environnement de dev (pour le profiler...)
            return;
        }

        // Si ce n'est pas une requête ajax, et si on n'est pas sur la page de login CAS ou déjà sur la page de maintenance
        // Dans ce cas on peut rediriger vers la page de maintenance
        if (!$request->isXmlHttpRequest() && !str_contains($request->getPathInfo(), '/cas/') && !str_contains($request->getPathInfo(), 'maintenance')) {
            if ($this->siamu->isMaintenanceModeRequired()) {
                // on redirige vers la page de maintenance
                $event->setResponse(new RedirectResponse($this->router->generate('app_siamu_maintenance')));
            }
        }
    }
}