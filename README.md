# Collection

[![Build Status](https://secure.travis-ci.org/goodby/collection.png?branch=master)](https://travis-ci.org/goodby/collection)

## What is Collection?

editing...

## Requirements

editing...

## Installation

Install composer in your project:

```
curl -s http://getcomposer.org/installer | php
```

Create a `composer.json` file in your project root:

```json
{
    "require": {
        "goodby/collection": "*"
    }
}
```

Install via composer:

```
php composer.phar install
```

## License

Collection is open-sourced software licensed under the MIT License - see the LICENSE file for details

## Documentation

editing...


## Contributing

Checkout master source code from github:

```
hub clone goodby/collection
```

Install develpment components via composer:

```
# If you don't have composer.phar
./scripts/bundle-devtools.sh .

# If you have composer.phar
composer.phar install --dev
```

### Unit Testing

We works under test driven development.

Run phpunit:

```
./vendor/bin/phpunit
```

### Coding Standard

We follows coding standard [PSR-2][].

Check if your codes follows PSR-2 by phpcs:

```
./vendor/bin/phpcs --standard=PSR2 src/
```

## Acknowledgement

This project was automatically generated by "[Goodby Setup](http://bit.ly/byesetup)". 

[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

