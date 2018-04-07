<?php

namespace EyesonTeam\Eyeson;

use EyesonTeam\Eyeson\Utils\Api;
use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Resource\Room;
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
  public function join($user, $id=null) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    return (new Room($this->api, $id))->join($user);
  }

  /**
   * Add a webhook in order to receive events on resource updates.
   *
   * @param string $targetUrl webhook target endpoint, your side ;)
   * @param string $type one of EyesonTeam\Eyeson\Resource\Webhook::TYPES
   * @return bool
   **/
  public function addWebhook($targetUrl, $type) {
    return (new Webhook($this->api, $targetUrl, $type))->save();
  }
}
