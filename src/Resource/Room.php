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
   *  exit_url ... URL destination for the exit button.
   *  recording_available ... allow recordings.
   *  broadcast_available ... allow broadcasting.
   *  layout_available ... allow user layout updates.
   *  reaction_available ... allow gif reactions.
   *  guest_token_available ... allow inviting guests.
   **/
  const OPTIONS = ['show_names', 'show_label', 'exit_url',
    'recording_available', 'broadcast_available', 'layout_available',
    'reaction_available', 'guest_token_available'];

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

  /**
   * Force to stop a running meeting.
   *
   * @return boolean
   **/
  public function destroy() {
    return $this->api->delete('/rooms/' . $this->id);
  }
}
