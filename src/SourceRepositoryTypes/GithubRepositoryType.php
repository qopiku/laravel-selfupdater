<?php

namespace Qopiku\Updater\SourceRepositoryTypes;

use Exception;
use InvalidArgumentException;
use Qopiku\Updater\Contracts\SourceRepositoryTypeContract;
use Qopiku\Updater\Events\UpdateAvailable;
use Qopiku\Updater\Exceptions\VersionException;
use Qopiku\Updater\Models\Release;
use Qopiku\Updater\Models\UpdateExecutor;
use Qopiku\Updater\SourceRepositoryTypes\GithubRepositoryTypes\GithubBranchType;
use Qopiku\Updater\SourceRepositoryTypes\GithubRepositoryTypes\GithubTagType;
use Qopiku\Updater\Traits\UseVersionFile;

class GithubRepositoryType
{
    use UseVersionFile;

    const BASE_URL = 'https://api.github.com';

    protected array $config;

    protected UpdateExecutor $updateExecutor;

    public function __construct(array $config, UpdateExecutor $updateExecutor)
    {
        $this->config = $config;
        $this->updateExecutor = $updateExecutor;
    }

    public function create(): SourceRepositoryTypeContract
    {
        if (empty($this->config['repository_vendor']) || empty($this->config['repository_name'])) {
            throw new \Exception('"repository_vendor" or "repository_name" are missing in config file.');
        }

        if ($this->useBranchForVersions()) {
            return resolve(GithubBranchType::class);
        }

        return resolve(GithubTagType::class);
    }

    /**
     * @throws \Exception
     */
    public function update(Release $release): bool
    {
        return $this->updateExecutor->run($release);
    }

    protected function useBranchForVersions(): bool
    {
        return ! empty($this->config['use_branch']);
    }

    public function getVersionInstalled(): string
    {
        return (string) config('self-update.version_installed');
    }

    /**
     * Check repository if a newer version than the installed one is available.
     * For updates that are pulled from a commit just checking the SHA won't be enough. So we need to check/compare
     * the commits and dates.
     *
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function isNewVersionAvailable(string $currentVersion = ''): bool
    {
        $version = $currentVersion ?: $this->getVersionInstalled();

        if (! $version) {
            throw VersionException::versionInstalledNotFound();
        }

        $versionAvailable = $this->getVersionAvailable(); //@phpstan-ignore-line

        if (version_compare($version, $versionAvailable, '<')) {
            if (! $this->versionFileExists()) {
                $this->setVersionFile($versionAvailable);
            }
            event(new UpdateAvailable($versionAvailable));

            return true;
        }

        return false;
    }
}
