<?php

namespace App\EventSubscriber;

use App\Entity\Posts;
use App\Message\GenerateSitemapMessage;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

class PostsSitemapSubscriber implements EventSubscriber
{
    private MessageBusInterface $bus;
    private bool $needsUpdate = false;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postFlush,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->checkEntity($args->getObject());
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->checkEntity($args->getObject());
    }

    private function checkEntity($entity): void
    {
        if (!$entity instanceof Posts) {
            return;
        }

        if ($entity->isIsPublished()) {
            $this->needsUpdate = true;
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if ($this->needsUpdate) {
            $this->bus->dispatch(new GenerateSitemapMessage());
            $this->needsUpdate = false;
        }
    }
}
