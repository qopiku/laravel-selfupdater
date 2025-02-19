<?php

namespace Qopiku\Updater\Events;

class UpdateAvailable
{
    protected string $newVersion;

    public function __construct(string $newVersion)
    {
        $this->newVersion = $newVersion;
    }

    public function getVersionAvailable(): string
    {
        return $this->newVersion;
    }
}
