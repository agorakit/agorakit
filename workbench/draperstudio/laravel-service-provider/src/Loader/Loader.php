<?php

/*
 * This file is part of Laravel Service Provider.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\ServiceProvider\Loader;

use Illuminate\Filesystem\Filesystem;

abstract class Loader
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The path to the application's packages.
     *
     * @var string
     */
    protected $packagePath;

    /**
     * Create a new publisher instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string                            $publishPath
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Publish files from a given path.
     *
     * @param string $package
     * @param string $source
     *
     * @return bool
     */
    public function getFileList($package)
    {
        return $this->getSource($package, $this->packagePath);
    }

    /**
     * Set the default package path.
     *
     * @param string $packagePath
     */
    public function setPackagePath($packagePath)
    {
        $this->packagePath = $packagePath;
    }

    /**
     * Get the source directory to publish.
     *
     * @param string $packagePath
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    abstract protected function getSource($packagePath);
}
