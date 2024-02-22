<?php

namespace Qopiku\Updater\Events;

use Qopiku\Updater\Models\Release;

class UpdateFailed
{
    protected Release $release;

    public function __construct(Release $release)
    {
        $this->release = $release;
    }
}
