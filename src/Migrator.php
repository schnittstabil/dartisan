<?php

namespace Schnittstabil\Dartisan;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Filesystem\Filesystem;

class Migrator extends \Illuminate\Database\Migrations\Migrator
{
    /**
     * @var Output
     */
    protected $dartisanOutput;

    public function __construct(
        MigrationRepositoryInterface $repository,
        Resolver $resolver,
        Filesystem $files,
        OutputInterface $output
    ) {
        parent::__construct($repository, $resolver, $files);
        $this->dartisanOutput = $output;
    }

    protected function note($message): void
    {
        $this->dartisanOutput->raw($message);
    }
}
