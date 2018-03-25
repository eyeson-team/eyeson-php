<?php

namespace EyesonTeam\Eyeson;

use EyesonTeam\Eyeson\Utils\Api;
use EyesonTeam\Eyeson\Utils\Room;

class Eyeson {
  private $api;

  public function __construct($key, $endpoint = 'https://api.eyeson.team') {
    $this->api = new Api($endpoint, $key);
  }

  /**
   * Join an eyeson room using a identifier to ensure people land in the same
   * meeint and a string uniquely identifing a user.
   *
   * @param string $id
   * @param string $user
   * @return Eyeson\Room
   **/
  public function join($id, $user) {
    return (new Room($this->api, $id))->join($user);
  }
}
