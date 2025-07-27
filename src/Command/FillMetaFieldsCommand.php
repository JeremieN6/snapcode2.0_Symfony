<?php

namespace App\Command;

use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fill-meta-fields',
    description: 'Remplit automatiquement les metaTitle et metaDescription vides des articles.'
)]
class FillMetaFieldsCommand extends Command
{
    public function __construct(
        private PostsRepository $postsRepository,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posts = $this->postsRepository->findAll();
        foreach ($posts as $post) {
            if (!$post->getMetaTitle() && $post->getTitle()) {
                $post->setMetaTitle(substr($post->getTitle(), 0, 60));
            }
            if (!$post->getMetaDescription() && $post->getContent()) {
                $desc = strip_tags($post->getContent());
                $post->setMetaDescription(substr($desc, 0, 160));
            }
        }
        $this->em->flush();
        $output->writeln('✅ Champs metaTitle et metaDescription remplis pour les articles existants.');
        return Command::SUCCESS;
    }
}