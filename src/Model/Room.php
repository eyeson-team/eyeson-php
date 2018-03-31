<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Model\Room as Response;

class Room {
  private $api, $id, $data;

  public function __construct($api, $id) {
    $this->api = $api;
    $this->id = $id;
  }

  /**
   * Join the room.
   *
   * @param Eyeson\Model\User $user
   * @return Eyeson\Model\Room
   **/
  public function join(User $user) {
    $result = $this->api
      ->post('/rooms', \array_merge(["id" => $id], $user->toArray()));
    return new Response($result);
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
