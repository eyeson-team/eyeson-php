
# eyeson-php

[![Build Status](https://travis-ci.org/eyeson-team/eyeson-php.svg?branch=master)](https://travis-ci.org/eyeson-team/eyeson-php)

eyeson.team PHP library - create powerful video conferences on demand and
easily integrate eyeson with your own PHP applications.

The library offers basic features of [eyeson API][eyeson]. See the [API
documentation][api-doc] to get a full overview, create an [issue][php-issues] if
you found a bug or have a feature request. Feel free to add an [issue at the
documentation repo][api-issues] for any general questions you might have.

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
// You can add additional details to your user, which will be shown in the
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

Before running any meeting related function like record, layout, or shutdown,
make sure that the meeting/room is ready.

```php
if (!$room->isReady()) {
  $room = $eyeson->waitReady($room);
}
```

You can control the meeting using a joined room, the actions will be triggered
by the user who joined, use a control user on demand.

```php
// Send chat message
$eyeson->sendMessage($room, 'hello world!');

// Start a video playback.
$playback = $eyeson->playback($room, [
  'url' => 'https://myapp.com/assets/video.webm',
  'audio' => true
]);
$playback->start();

// Start and stop a recording.
$recording = $eyeson->record($room);
$recording->start();
// later...
$recording->stop();
// check if recording is active
$recording->isActive();
// fetch recording details if needed
$eyeson->getRecordingById($recordingId);

// Create a snapshot
$eyeson->createSnapshot($room);
// fetch snapshot details if needed
$eyeson->getSnapshotById($snapshotId);

// Force stop a running meeting.
$eyeson->shutdown($room);
```

Register webhooks to receive updates like new meetings, or recordings in your
application.

```php
// Register a webhook
$eyeson->addWebhook('https://my.application/hooks/recordings',
                    'recording_update');

// Clear webhook if not needed anymore
$eyeson->clearWebhook();
```

You can switch from the automatic layout handling to a custom layout and set
user positions for the video podium. Note: Use an empty string for
an empty position. Additionally, you can hide/show the name inserts in the
video.

```php
$layout = $eyeson->layout($room);
$layout->apply([
  'layout' => 'auto',
  'name' => 'present-lower-3',
  'users' => ["5eb3a...994", "5eb3a...d06", ...],
  'voice_activation' => true,
  'show_names' => false
]);
// switch back to automatic layout
$layout->useAuto();
// apply fixed custom layout
$layout->update($userList); // ["5eb3a...994", "5eb3a...d06"]
$layout->showNames();
$layout->hideNames();
```

Apply overlay and background images. You can send plain text that will
automatilcally create an overlay.

```php
$layer = $eyeson->layer($room);
$layer->apply([
  'url' => 'https://myapp.com/assets/meetingBackground.jpg',
  'z-index' => -1
]);

$layer->setText('Hello World!');
$layer->setText('This is hot.', 'News!');
$layer->setText('This has an icon.', '', 'https://myapp.com/assets/icon.png');

$layer->setImageURL('https://myapp.com/assets/meetingForeground.png');
$layer->setImageURL('https://myapp.com/assets/meetingBackground.jpg', -1);

$layer->clear();
$layer->clear(-1);
```

## Error handling

API requests can throw an `EyesonApiError` which is an instance of the PHP
`Exception`. Its `getMessage()` method contains the API response error message
and `getCode()` contains the API response status code.

```php
use EyesonTeam\Eyeson\Exception\EyesonApiError;

function startRecording($accessKey) {
  try {
    $recording = $eyeson->record($accessKey);
    return $recording->start();
  } catch (EyesonApiError $error) {
    error_log($error->getCode() . ' - ' . $error->getMessage());
    return false;
  }
}

startRecording($accessKey);
```

## Permalink API

Since v2.2.0, eyeson-php includes functions to use with Permalink API. You can
read more about it here: https://docs.eyeson.com/docs/rest/features/permalink

```php
$eyeson = new Eyeson('<your-eyeson-api-key>');

$permalink = $eyeson->permalink->create('<username>', ['name' => '<room_name>', 'widescreen' => true]);
echo $permalink->getId();
echo $permalink->getUrl();
echo $permalink->getGuestUrl();
echo $permalink->getUserToken();
echo $permalink->getGuestToken();

$permalink = $eyeson->permalink->update('<permalink-id>', ['widescreen' => false]);
$permalink = $eyeson->permalink->getById('<permalink-id>');
$permalink = $eyeson->permalink->getAll(['page' => 1, 'limit' => 50, 'expired' => false]);
$permalink = $eyeson->permalink->addUser('<permalink-id>', '<username>', ['id' => '<user-id>']);
$eyeson->permalink->removeUser('<permalink-id>', '<user-token>');
$room = $eyeson->permalink->joinMeeting('<user-token>');
$room = $eyeson->permalink->registerGuest('<username>', '<guest-token>', ['id' => '<user-id>']); # works only if $permalink->isStarted() === true
$eyeson->permalink->delete('<permalink-id>');
```

## Install the library using Composer

```sh
# required php version >= 5.4
$ composer require eyeson/eyeson-php
```

## Change log

See [CHANGELOG.md](./CHANGELOG.md).

## Development

You can use docker to run the testsuite, see the [Makefile](/Makefile) for
details.

```sh
$ make build
$ make test
```

[eyeson]: https://www.eyeson.team "eyeson"
[api-doc]: https://eyeson-team.github.io/api "eyeson API Documentation"
[php-issues]: https://github.com/eyeson-team/eyeson-php/issues "eyeson PHP Issues"
[api-issues]: https://github.com/eyeson-team/api/issues "eyeson API issues"

[1]: https://hub.docker.com/_/php/ "PHP Docker Image"
