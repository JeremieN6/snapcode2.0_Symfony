<?php

namespace App\MessageHandler;

use App\Message\GenerateSitemapMessage;
use App\Service\SitemapGenerator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GenerateSitemapHandler implements MessageHandlerInterface
{
    private SitemapGenerator $generator;

    public function __construct(SitemapGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function __invoke(GenerateSitemapMessage $message): void
    {
        $this->generator->generate();
    }
}
