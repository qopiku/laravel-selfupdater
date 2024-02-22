<?php

namespace Qopiku\Updater\Events;

use Qopiku\Updater\Models\Release;

class UpdateSucceeded
{
    protected Release $release;

    public function __construct(Release $release)
    {
        $this->release = $release;
    }

    public function getVersionUpdatedTo(): ?string
    {
        return $this->release->getVersion();
    }
}
