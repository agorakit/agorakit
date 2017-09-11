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

class MigrationPublisher extends Publisher
{
    /**
     * Get the source assets directory to publish.
     *
     * @param string $packagePath
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getSource($packagePath)
    {
        $sources = [
            "{$packagePath}/resources/database/migrations",
            "{$packagePath}/resources/migrations",
            "{$packagePath}/migrations",
        ];

        // iterate through all possible locations
        foreach ($sources as $source) {
            if ($this->files->isDirectory($source)) {
                $paths = [];

                // get all files of the current directory
                $files = $this->getSourceFiles($source);

                // iterate through all files
                foreach ($files as $file) {
                    // if the destination doesn't exist we can add the file to the queue
                    if (!class_exists($this->getClassFromFile($file))) {
                        $paths[$file] = $this->getDestinationPath('migrations', [
                            date('Y_m_d_His', time()), $this->getFileName($file),
                        ]);
                    }
                }

                return $paths;
            }
        }

        throw new InvalidArgumentException('Migrations not found.');
    }
}
