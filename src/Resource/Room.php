<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Model\Room as Response;

/**
 * Room Resource.
 **/
class Room {
  private $api, $id, $options = [];
  /**
   * Room OPTIONS
   *  show_names ... show/hide display names.
   *  show_label ... show/hide eyeson brand.
   *  recording_available ... allow recordings.
   *  broadcast_available ... allow broadcasting.
   *  layout_available ... allow user layout updates.
   **/
  const OPTIONS = ['show_names', 'show_label', 'recording_available',
    'broadcast_available', 'layout_available'];

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
    $params = \array_merge(
      [ "id" => $this->id ],
      $this->prefix($user->toArray(), 'user'),
      $this->prefix($this->options, 'options')
    );

    return new Response($this->api->post('/rooms', $params));
  }

  /**
   * Set room options.
   *
   * @param array $options see Room::OPTIONS
   * @return Eyeson\Resource\Room
   **/
  public function setOptions(array $options) {
    $this->options = \array_filter($options, function($key) {
      return \in_array($key, self::OPTIONS);
    }, ARRAY_FILTER_USE_KEY);
    return $this;
  }

  private function prefix($arr, $prefix) {
    foreach(\array_keys($arr) as $key) {
      $arr[$prefix . '[' . $key . ']'] = $arr[$key];
      unset($arr[$key]);
    }
    return $arr;
  }
}
