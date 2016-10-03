# Laravel Taggable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require draperstudio/laravel-taggable
```

And then, if using Laravel 5, include the service provider within `app/config/app.php`.

``` php
'providers' => [
    // ... Illuminate Providers
    // ... App Providers
    DraperStudio\Taggable\ServiceProvider::class
];
```

### Migration

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="DraperStudio\Taggable\ServiceProvider"
```

And then run the migrations to setup the database table.

```bash
$ php artisan migrate
```

### Configuration

Taggable supports optional configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="DraperStudio\Taggable\ServiceProvider"
```

This will create a `config/taggable.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

## Usage

##### Setup a Model

``` php
<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use DraperStudio\Taggable\Contracts\Taggable;
use DraperStudio\Taggable\Traits\Taggable as TaggableTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Taggable
{
    use TaggableTrait;

    protected $onlyUseExistingTags = false;
}
```

##### Add a tag to a model

``` php
$model->tag('Apple,Banana,Cherry');
$model->tag(['Apple', 'Banana', 'Cherry']);
```

##### Remove specific tags

``` php
$model->untag('Banana');
```

##### Remove all tags

``` php
$model->detag();
```

##### Remove all assigned tags and assign the new ones

``` php
$model->retag('Etrog,Fig,Grape');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@draperstudio.tech instead of using the issue tracker.

## Credits

- [DraperStudio][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/DraperStudio/laravel-taggable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/DraperStudio/Laravel-Taggable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DraperStudio/laravel-taggable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/DraperStudio/laravel-taggable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DraperStudio/laravel-taggable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/DraperStudio/laravel-taggable
[link-travis]: https://travis-ci.org/DraperStudio/Laravel-Taggable
[link-scrutinizer]: https://scrutinizer-ci.com/g/DraperStudio/laravel-taggable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DraperStudio/laravel-taggable
[link-downloads]: https://packagist.org/packages/DraperStudio/laravel-taggable
[link-author]: https://github.com/DraperStudio
[link-contributors]: ../../contributors
