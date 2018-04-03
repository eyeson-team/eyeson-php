
# eyeson-php

eyeson.team library for PHP create powerfull video conferences on demand,
integrated in your own PHP application.

The library offers some basic features of [eyeson.team][eyeson]. See the [API
documentation][api-doc] to get a full overview, create an [issue][php-issues]
if you found a bug or have any feature requests. Feel free to add an
[issues][api-issues] at the documentation for any general question.

## Usage

```php
$eyeson = new Eyeson('<your-eyeson-api-key>');
// Join a new eyeson video meeting by providing a users name.
$room = $eyeson->join('Mike', 'standup meeting');
$room->getUrl(); // https://app.eyeson.team?<token> URL to eyeson.team video GUI
// If you do not provide a room name, eyeson will create one for you. Note that
// users will join different rooms on every request because every call will
// create a new one.
$room = $eyeson->join('mike@eyeson.team');
// You can add some details of your user to be shown in the GUI. Choosing a
// unique identifer will keep the user distinct and ensure actions to be mapped
// on this record. E.g. joining the room two times will not lead to two
// different participants in a meeting.
$user = [
  'id' => 'mike@eyeson.team',
  'name' => 'Mike',
  'avatar' => 'https://mikes.website/avatar.png'
];
$room = $eyeson->join($user, 'daily standup');
```

## Install the Library using Composer

```sh
# required php version >= 5.4
$ composer require eyeson.team/eyeson-php
```

## Development

You can use docker to run the testsuite, see the [Makefile](/Makefile) for
details.

```sh
$ make build
$ make test
```

[eyeson]: https://www.eyeson.team "eyeson team"
[api-doc]: https://eyeson-team.github.com/api "eyeson API Documentation"
[php-issues]: https://github.com/eyeson-team/eyeson-php/issues "eyeson PHP Issues"
[api-issues]: https://github.com/eyeson-team/api/issues "eyeson API issues"

[1]: https://hub.docker.com/_/php/ "PHP Docker Image"
