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

use InvalidArgumentException;

class ConfigPublisher extends Publisher
{
    /**
     * Get the source configuration directory to publish.
     *
     * @param string $packagePath
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function getSource($packagePath)
    {
        $sources = [
            "{$packagePath}/resources/config",
            "{$packagePath}/config",
        ];

        // iterate through all possible locations
        foreach ($sources as $source) {
            if ($this->files->isDirectory($source)) {
                $paths = [];

                // get all files of the current directory
                $files = $this->getSourceFiles($source);

                // iterate through all files
                foreach ($files as $file) {
                    $destinationPath = $this->getDestinationPath('config', [
                        $this->getFileName($file),
                    ]);

                    // if the destination doesn't exist we can add the file to the queue
                    if (!$this->files->exists($destinationPath)) {
                        $paths[$file] = $destinationPath;
                    }
                }

                return $paths;
            }
        }

        throw new InvalidArgumentException('Configuration not found.');
    }
}
