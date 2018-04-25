
# eyeson-php

[![Build Status](https://travis-ci.org/eyeson-team/eyeson-php.svg?branch=master)](https://travis-ci.org/eyeson-team/eyeson-php)

eyeson.team PHP library - create powerful video conferences on demand and
easily integrate eyeson with your own PHP applications.

The library offers basic features of [eyeson.team][eyeson]. See the [API
documentation][api-doc] to get a full overview, create an [issue][php-issues] if
you found a bug or have a feature request. Feel free to add an [issue at the
documentation repo][api- issues] for any general questions you might have.

## Usage

Provide your api key and quickly join any room using the join method. You can
optionally provide [configuration options](/src/Resource/Room.php#L13) as a 3rd
argument.

```php
$eyeson = new Eyeson('<your-eyeson-api-key>');
// Join a new eyeson video meeting by providing a user's name.
$room = $eyeson->join('Mike', 'standup meeting');
$room->getUrl(); // https://app.eyeson.team?<token> URL to eyeson.team video GUI
// If you do not provide a room name, eyeson will create one for you. Note that
// users **will join different rooms on every request**.
$room = $eyeson->join('mike@eyeson.team');
// You can add additional details to your user, which will be be shown in the
// GUI. Choosing a unique identifier will keep the user distinct and ensures
// actions are mapped correctly to this record. E.g. joining the room twice will
// not lead to two different participants in a meeting.
$user = [
  'id' => 'mike@eyeson.team',
  'name' => 'Mike',
  'avatar' => 'https://mikes.website/avatar.png'
];
$room = $eyeson->join($user, 'daily standup');
```

Register webhooks to receive updates like new meetings, or recordigns in your
application.

```php
// Register a webhook
$eyeson->addWebhook('https://my.application/hooks/recordings',
                    'recording_update');
```

## Install the library using Composer

```sh
# required php version >= 5.4
$ composer require eyeson/eyeson-php
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
