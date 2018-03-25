<?php

namespace EyesonTeam\Eyeson\Utils;

class Room {
  private $api, $id, $data;

  public function __construct($api, $id) {
    $this->api = $api;
    $this->id = $id;
  }

  /**
   * Join the room.
   *
   * @param string $user
   * @return Eyeson\Room
   **/
  public function join($user) {
    $this->data = $this->api
      ->post('/rooms', [ "id" => $id, "user[id]" => $user ]);
    return $this;
  }

  /**
   * Url to eyeson web GUI.
   *
   * @return string
   **/
  public function getUrl() {
    return $this->data['links']['gui'];
  }
}
