# Change Log

eyeson-php change log

## v2.4.0 / 2025-04-03

- new: Forward stream support
    - $forward = $eyeson->forward($room)
    - $forward->source('<forward-id>', '<user-id>', 'audio,video', 'https://...')
    - etc.
- new: $eyeson->getUsersList($room, $isOnline)
- new: $eyeson->deleteRecordingById($recordingId)
- new: $eyeson->getRecordingsList($room, $since, $until, $page)
- new: $eyeson->deleteSnapshotById($snapshotId)
- new: $eyeson->getSnapshotsList($room, $since, $until, $page)

## v2.3.0 / 2025-03-28

- new: $layer->sendImageFile($path, $zIndex)
- deprecate warning: $layer->setText()

## v2.2.0 / 2024-06-28

- new: Permalink support
    - $eyeson->permalink->create('<user-name>', ['name'=>'<room-name>'])
    - etc.
- new: $eyeson->join('John Doe', null, ['widescreen' => true])

## v2.1.2 / 2023-06-15

- new: $eyeson->join('John Doe', null, ['background_color' => '#121212'])

## v2.1.1 / 2023-03-20

- new: $recording = $eyeson->record($room); $recording->isActive();

## v2.1.0 / 2023-02-13

- new: $eyeson->clearWebhook()
- new: $eyeson->getRecordingById($recordingId)
- new: $eyeson->createSnapshot($room)
- new: $eyeson->getSnapshotById($snapshotId)
- new: EyesonApiError
    all API request errors are instances of EyesonApiError including
    getMessage() and getCode()
    message contains the API response message and code the http status code

## v2.0.0 / 2023-02-07

- breaking change! $eyeson->getLayout() renamed to $eyeson->layout()
- breaking change! $eyeson->record() does NOT automatically start
- new: $recording = $eyeson->record($room); $recording->start();
- new: $eyeson->layout($room)->apply($options)
- new: $eyeson->playback($room, $options) start/stop
- new: $eyeson->sendMessage($room, $content, ...)
- new: $eyeson->layer($room) incl. apply($options), setImageURL(), setText(), clear()
- all meeting functions accept $accessKey string instead of $room object

## v1.5.2 / 2023-02-03

- new: $eyeson->waitReady($room)
- bugfix: $recording->stop()
- update: $eyeson->record($accessKey)