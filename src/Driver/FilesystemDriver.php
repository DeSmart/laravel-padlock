<?php

namespace DeSmart\Padlock\Driver;

use Carbon\Carbon;
use DeSmart\Padlock\Entity\Padlock;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class FilesystemDriver implements PadlockDriverInterface
{
    /** @var Filesystem */
    private $filesystem;

    const SUBDIR = 'padlocks';

    /**
     * FilesystemDriver constructor.
     * @param FilesystemManager $filesystemManager
     */
    public function __construct(FilesystemManager $filesystemManager)
    {
        $this->filesystem = $filesystemManager->disk('local');
    }

    /**
     * @param Padlock $padlock
     * @return string
     */
    private function getPath(Padlock $padlock)
    {
        return self::SUBDIR . '/' . $padlock->getName() . ".lock";
    }

    private function getNameFromPath(string $path)
    {
        preg_match("#^" . self::SUBDIR . '/' . "(.*)\.lock$#", $path, $matches);

        return $matches[1];
    }

    /**
     * @param string $path
     * @return Carbon
     */
    private function getCreatedAt(string $path): Carbon
    {
        $timestamp = $this->filesystem->get($path);

        return Carbon::createFromFormat('U', $timestamp, new \DateTimeZone('UTC'));
    }

    /**
     * @param Padlock $padlock
     */
    public function lock(Padlock $padlock)
    {
        $this->filesystem->put($this->getPath($padlock), Carbon::now(new \DateTimeZone('UTC'))->timestamp);
    }

    /**
     * @param Padlock $padlock
     */
    public function unlock(Padlock $padlock)
    {
        $this->filesystem->delete($this->getPath($padlock));
    }

    /**
     * @param string $name
     * @return Padlock|null
     */
    public function get(string $name)
    {
        $path = $this->getPath(new Padlock($name));

        if (false === $this->filesystem->exists($path)) {
            return null;
        }

        return new Padlock($name, $this->getCreatedAt($path));
    }

    /**
     * @return Padlock[]|Collection
     */
    public function getAll()
    {
        return collect($this->filesystem->allFiles(self::SUBDIR))->map(function($path) {
            return new Padlock($this->getNameFromPath($path), $this->getCreatedAt($path));
        });
    }
}
