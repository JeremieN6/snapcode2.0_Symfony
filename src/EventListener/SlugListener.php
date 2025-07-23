<?php

namespace App\EventListener;

use App\Entity\Posts;
use App\Entity\Categories;
use App\Entity\Keywords;
use App\Service\SlugService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Posts::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Posts::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Categories::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Categories::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Keywords::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Keywords::class)]
class SlugListener
{
    public function __construct(
        private SlugService $slugService
    ) {
    }

    public function prePersist($entity): void
    {
        $this->generateSlugForEntity($entity);
    }

    public function preUpdate($entity): void
    {
        $this->generateSlugForEntity($entity);
    }

    private function generateSlugForEntity($entity): void
    {

        if ($entity instanceof Posts) {
            if (empty($entity->getSlug()) && !empty($entity->getTitle())) {
                $entity->setSlug($this->slugService->generateSlug($entity->getTitle()));
            }
        } elseif ($entity instanceof Categories) {
            if (empty($entity->getSlug()) && !empty($entity->getName())) {
                $entity->setSlug($this->slugService->generateSlug($entity->getName()));
            }
        } elseif ($entity instanceof Keywords) {
            if (empty($entity->getSlug()) && !empty($entity->getName())) {
                $entity->setSlug($this->slugService->generateSlug($entity->getName()));
            }
        }
    }
}
