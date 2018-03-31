<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;

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
   * @return Eyeson\Resource\Room
   **/
  public function join(User $user) {
    $params = \array_merge([ "id" => $this->id ],
                           $this->prefix($user->toArray(), 'user'));
    $this->data = $this->api->post('/rooms', $params);
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

  private function prefix($arr, $prefix) {
    foreach(\array_keys($arr) as $key) {
      $arr[$prefix . '[' . $key . ']'] = $arr[$key];
      unset($arr[$key]);
    }
    return $arr;
  }
}
