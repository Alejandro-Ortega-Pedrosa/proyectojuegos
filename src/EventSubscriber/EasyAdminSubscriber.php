<?php

namespace App\EventSubscriber;

use App\Service\mail;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private mail $correo;
    private MailerInterface $mailer;

    public function __construct(Security $security, mail $correo, MailerInterface $mailer)
    {
        $this->security = $security;
        $this->correo =$correo;
        $this->mailer =$mailer;
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $question = $event->getEntityInstance();
        if (!$question instanceof User) {
            return;
        }

        $correo="alexo.02@hotmail.com";

        $this->correo->sendEmail($correo, $this->mailer);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
