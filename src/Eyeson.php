<?php

namespace EyesonTeam\Eyeson;

use EyesonTeam\Eyeson\Utils\Api;
use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Resource\Layout;
use EyesonTeam\Eyeson\Resource\Room;
use EyesonTeam\Eyeson\Resource\Recording;
use EyesonTeam\Eyeson\Resource\Webhook;

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
   * @return Eyeson\Room
   **/
  public function join($user, $id=null, array $options=array()) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    return (new Room($this->api, $id))
      ->setOptions($options)
      ->join($user);
  }

  /**
   * Force shutdown a running meeting.
   **/
  public function shutdown($room) {
    return (new Room($this->api, $room->getId()))->destroy();
  }

  /**
   * Start a recording.
   *
   * @return EyesonTeam\Eyeson\Resource\Recording
   **/
  public function record($room) {
    $rec = new Recording($this->api, $room->getAccessKey());
    $rec->start();
    return $rec;
  }

  /**
   * Get a rooms video layout.
   *
   * @return EyesonTeam\Eyeson\Resource\Layout
   **/
  public function getLayout($room) {
    $layout = new Layout($this->api, $room->getAccessKey());
    return $layout;
  }

  /**
   * Add a webhook in order to receive events on resource updates.
   *
   * @param string $targetUrl webhook target endpoint, your side ;)
   * @param string|array $types array or comma-separated types
   * @return bool
   * @see EyesonTeam\Eyeson\Resource\Webhook::TYPES
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
