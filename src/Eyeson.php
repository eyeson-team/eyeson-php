<?php

namespace EyesonTeam\Eyeson;

use EyesonTeam\Eyeson\Utils\Api;
use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Resource\Layout;
use EyesonTeam\Eyeson\Resource\Room;
use EyesonTeam\Eyeson\Resource\Recording;
use EyesonTeam\Eyeson\Resource\Webhook;
use EyesonTeam\Eyeson\Resource\Playback;
use EyesonTeam\Eyeson\Resource\Message;
use EyesonTeam\Eyeson\Resource\Layer;
use EyesonTeam\Eyeson\Resource\Snapshot;
use EyesonTeam\Eyeson\Resource\PermalinkAPI;
use EyesonTeam\Eyeson\Resource\Forward;

class Eyeson {
  private $api;
  public $permalink;

  /**
   * @param string $key your eyeson api key
   * @param string $endpoint (optional) api endpoint, set in test mode
   **/
  public function __construct($key, $endpoint = 'https://api.eyeson.team') {
    $api = new Api($endpoint, $key);
    $this->api = $api;
    $this->permalink = new PermalinkAPI($api);
  }

  /**
   * Join an eyeson room using a identifier to ensure people land in the same
   * meeint and a string uniquely identifing a user.
   *
   * @param mixed $user provide an string(id), array or Eyeson\Model\User
   * @param string $id (optional) identifer for your room
   * @param array options (optional)
   * @return Eyeson\Model\Room
   **/
  public function join($user, $id = null, array $options = []) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    $room = new Room($this->api, $id);
    if(\array_key_exists('name', $options)) {
      $room->setName($options['name']);
      unset($options['name']);
    }
    return $room->setOptions($options)->join($user);
  }

  /**
   * Fetch room data until room is ready.
   * 
   * @param Eyeson\Model\Room room
   * @return Eyeson\Model\Room updated room object
   **/
  public function waitReady($room) {
    if ($room->isReady()) {
      return $room;
    }
    $roomResource = new Room($this->api, $room->getId());
    return $roomResource->waitReady($room->getAccessKey());
  }

  /**
   * Force shutdown a running meeting.

   * @param mixed Eyeson\Model\Room or string roomId
   * @return bool
   **/
  public function shutdown($room) {
    if (is_string($room)) {
      return (new Room($this->api, $room))->destroy();
    } else {
      return (new Room($this->api, $room->getId()))->destroy();
    }
  }

  /**
   * Get recording object to start and stop.
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @return Eyeson\Resource\Recording
   **/
  public function record($room) {
    if (is_string($room)) {
      $recording = new Recording($this->api, $room);
    } else {
      $recording = new Recording($this->api, $room->getAccessKey());
    }
    return $recording;
  }

  /**
   * Get recording by recordingId.
   *
   * @param string recordingId
   * @return object recording
   **/
  public function getRecordingById($recordingId) {
    return (new Recording($this->api, ''))->getById($recordingId);
  }

  /**
   * Delete recording by recordingId.
   *
   * @param string recordingId
   * @return bool
   **/
  public function deleteRecordingById($recordingId) {
    return (new Recording($this->api, ''))->delete($recordingId);
  }

  /**
   * Get list of all recordings of a room
   * @param Eyeson\Model\Room|string Room or room_id
   * @param string since - ISO8601 Timestamp
   * @param string until - ISO8601 Timestamp
   * @param int page (optional), default 1
   * @return object[] recordings
   */
  public function getRecordingsList($room, $since = '', $until = '', $page = 1) {
    if (is_string($room)) {
      $room_id = $room;
    } else {
      $room_id = $room->getId();
    }
    $filter = ['page' => $page];
    if ($since) {
      $filter['since'] = $since;
    }
    if ($until) {
      $filter['until'] = $until;
    }
    $list = $this->api->get('/rooms/' . $room_id . '/recordings?' . http_build_query($filter));
    return $list;
  }

  /**
   * Get layout object.
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @return Eyeson\Resource\Layout
   **/
  public function layout($room) {
    if (is_string($room)) {
      $layout = new Layout($this->api, $room);
    } else {
      $layout = new Layout($this->api, $room->getAccessKey());
    }
    return $layout;
  }

  /**
   * Get layer object.
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @return Eyeson\Resource\Layer
   **/
  public function layer($room) {
    if (is_string($room)) {
      $layer = new Layer($this->api, $room);
    } else {
      $layer = new Layer($this->api, $room->getAccessKey());
    }
    return $layer;
  }

  /**
   * Get playback object
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @param array options
   * @return Eyeson\Resource\Playback
   * @see Eyeson\Resource\Playback options
   **/
  public function playback($room, $options = []) {
    if (is_string($room)) {
      $playback = new Playback($this->api, $room, $options);
    } else {
      $playback = new Playback($this->api, $room->getAccessKey(), $options);
    }
    return $playback;
  }

  /**
   * Send message
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @param string content
   * @param string type (optional)
   * @return bool
   **/
  public function sendMessage($room, $content, $type = 'chat') {
    if (is_string($room)) {
      return (new Message($this->api, $room, $type, $content))->send();
    } else {
      return (new Message($this->api, $room->getAccessKey(), $type, $content))->send();
    }
  }

  /**
   * Create snapshot
   *
   * @param mixed Eyeson\Model\Room or string accessKey
   * @return bool
   **/
  public function createSnapshot($room) {
    if (is_string($room)) {
      return (new Snapshot($this->api, $room))->create();
    } else {
      return (new Snapshot($this->api, $room->getAccessKey()))->create();
    }
  }

  /**
   * Get snapshot by snapshotId.
   *
   * @param string snapshotId
   * @return object snapshot
   **/
  public function getSnapshotById($snapshotId) {
    return (new Snapshot($this->api, ''))->getById($snapshotId);
  }

  /**
   * Delete snapshot by snapshotId.
   *
   * @param string snapshotId
   * @return bool
   **/
  public function deleteSnapshotById($snapshotId) {
    return (new Snapshot($this->api, ''))->delete($snapshotId);
  }

  /**
   * Get list of all snapshots of a room
   * @param Eyeson\Model\Room|string Room or room_id
   * @param string since - ISO8601 Timestamp
   * @param string until - ISO8601 Timestamp
   * @param int page (optional), default 1
   * @return object[] snapshots
   */
  public function getSnapshotsList($room, $since = '', $until = '', $page = 1) {
    if (is_string($room)) {
      $room_id = $room;
    } else {
      $room_id = $room->getId();
    }
    $filter = ['page' => $page];
    if ($since) {
      $filter['since'] = $since;
    }
    if ($until) {
      $filter['until'] = $until;
    }
    $list = $this->api->get('/rooms/' . $room_id . '/snapshots?' . http_build_query($filter));
    return $list;
  }

  /**
   * Add a webhook in order to receive events on resource updates.
   *
   * @param string $targetUrl webhook target endpoint, your side ;)
   * @param string|array $types array or comma-separated types
   * @return bool
   * @see Eyeson\Resource\Webhook::TYPES
   **/
  public function addWebhook($targetUrl, $types) {
    if (\is_string($types)) {
      $types = \explode(',', $types);
    }
    if (!\is_array($types)) {
      return false;
    }
    return (new Webhook($this->api, $targetUrl, $types))->save();
  }

  /**
   * Clear webhook if exist
   **/
  public function clearWebhook() {
    return (new Webhook($this->api, '', []))->clear();
  }

  /**
   * Get list of meeting participants (users)
   * @param Eyeson\Model\Room|string Room or room_id
   * @param bool|null isOnline (optional), default null
   * @return object[] users
   */
  public function getUsersList($room, $isOnline = null) {
    if (is_string($room)) {
      $room_id = $room;
    } else {
      $room_id = $room->getId();
    }
    $filter = '';
    if (\is_bool($isOnline)) {
      $filter = '?online=' . ($isOnline ? 'true' : 'false');
    }
    $list = $this->api->get('/rooms/' . $room_id . '/users' . $filter);
    return $list;
  }

  /**
   * Get room forward object
   *
   * @param Eyeson\Model\Room|string Room or room_id
   * @return Eyeson\Resource\Forward
   **/
  public function forward($room) {
    if (is_string($room)) {
      $room_id = $room;
    } else {
      $room_id = $room->getId();
    }
    return new Forward($this->api, $room_id);
  }
}
