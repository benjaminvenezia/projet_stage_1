<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
   private $themesService;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('themes', $this->themesService->getThemes());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
