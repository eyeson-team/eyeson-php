
# eyeson-php

eyeson.team Library for PHP create powerfull video conferences on demand,
integrate in your own PHP application.

## Usage

```php
$eyeson = new Eyeson('<your-eyeson-api-key>');
$room = $eyeson->join('my first video meeting');
$room->getUrl(); // https://app.eyeson.team?<token> URL to eyeson.team video GUI
```

## Install using composer

```sh
$ composer require eyeson.team/eyeson-php
```

## Development

You can use docker to run the testsuite, see the [Makefile](/Makefile) for
details.

```sh
$ make build
$ make test
```

[1]: https://hub.docker.com/_/php/ "PHP Docker Image"
