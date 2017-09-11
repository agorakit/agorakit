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

class RoutePublisher extends Publisher
{
    /**
     * Get the source configuration directory to publish.
     *
     * @param string $package
     * @param string $packagePath
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getSource($packagePath)
    {
        $sources = [
            "{$packagePath}/resources/routes",
            "{$packagePath}/routes",
        ];

        foreach ($sources as $source) {
            if ($this->files->isDirectory($source)) {
                return $source;
            }
        }

        throw new InvalidArgumentException('Configuration not found.');
    }
}
