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

class Eyeson {
  private $api;

  /**
   * @param string $key your eyeson api key
   * @param string $endpoint (optional) api endpoint, set in test mode
   **/
  public function __construct($key, $endpoint = 'https://api.eyeson.team') {
    $this->api = new Api($endpoint, $key);
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
   * Get recording object.
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
}
