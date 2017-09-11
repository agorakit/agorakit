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

class AssetPublisher extends Publisher
{
    /**
     * Get the source asset directory to publish.
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
            "{$packagePath}/resources/public",
            "{$packagePath}/public",
        ];

        foreach ($sources as $source) {
            if ($this->files->isDirectory($source)) {
                return [$source => $this->publishPath];
            }
        }

        throw new InvalidArgumentException('Assets not found.');
    }
}
