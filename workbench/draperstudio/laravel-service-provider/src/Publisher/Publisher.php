<?php

/*
 * This file is part of Laravel Service Provider.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\ServiceProvider\Publisher;

use Illuminate\Filesystem\Filesystem;

abstract class Publisher
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The destination of the config files.
     *
     * @var string
     */
    protected $publishPath;

    /**
     * The path to the application's packages.
     *
     * @var string
     */
    protected $packagePath;

    /**
     * The destination paths for source files.
     *
     * @var string
     */
    protected $destinationPaths;

    /**
     * Create a new publisher instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string                            $publishPath
     */
    public function __construct(Filesystem $files, $publishPath)
    {
        $this->files = $files;
        $this->publishPath = $publishPath;

        $this->destinationPaths = [
            'migrations'   => database_path('/migrations/%s_%s'),
            'seeds'        => database_path('/seeds/%s'),
            'config'       => config_path('%s'),
            'views'        => base_path('resources/views/vendor/%s'),
            'translations' => base_path('resources/lang/%s'),
            'assets'       => public_path('vendor/%s'),
            'routes'       => null,
        ];
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
     * Set the default package name.
     *
     * @param string $packageName
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * Get the source directory to publish.
     *
     * @param string $packagePath
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    abstract protected function getSource($packagePath);

    /**
     * @param $file
     *
     * @return string
     */
    protected function getFileName($file)
    {
        $file = basename($file);

        if (!ends_with($file, '.php')) {
            $file = $file.'.php';
        }

        return $file;
    }

    /**
     * Get the target destination path for the files.
     *
     * @param string $package
     *
     * @return string
     */
    protected function getDestinationPath($type, $args)
    {
        return vsprintf($this->destinationPaths[$type], $args);
    }

    /**
     * @param $type
     * @param $files
     *
     * @return array
     */
    protected function getSourceFiles($path)
    {
        $files = [];
        foreach (glob($path.'/*.php') as $file) {
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    protected function getClassFromFile($path)
    {
        $count = count($tokens = token_get_all(file_get_contents($path)));

        for ($i = 2; $i < $count; ++$i) {
            if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                return $tokens[$i][1];
            }
        }
    }
}
