<?php

namespace Qopiku\Updater\Contracts;

interface UpdaterContract
{
    /**
     * Get a source type instance.
     */
    public function source(string $name = ''): SourceRepositoryTypeContract;
}
