<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Model\Room as Response;

/**
 * Room Resource.
 **/
class Room {
  private $api, $id, $name, $options = [];

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
   *  logo ... URL destination of LOGO image
   *  locale ... locale shortname
   **/
  const OPTIONS = ['show_names', 'show_label', 'exit_url', 'logo', 'locale',
    'recording_available', 'broadcast_available', 'layout_available',
    'lock_available', 'kick_available', 'sfu_mode', 'hide_chat',
    'reaction_available', 'guest_token_available', 'virtual_background',
    'virtual_background_allow_guest', 'virtual_background_image',
    'virtual_background_allow_local_image'];
  const CUSTOM_OPTIONS = ['logo', 'locale', 'hide_chat', 'virtual_background',
    'virtual_background_allow_guest', 'virtual_background_image',
    'virtual_background_allow_local_image'];

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
      [ "id" => $this->id, "name" => $this->name ],
      $this->prefix($user->toArray(), 'user'),
      $this->prefix($this->options, 'options')
    );

    return new Response($this->api->post('/rooms', $params));
  }

  /**
   * Set room name
   *
   * @param string $name
   * @return Eyeson\Resource\Room
   **/
  public function setName(string $name) {
    $this->name = $name;
    return $this;
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
      if (\in_array($key, self::CUSTOM_OPTIONS)) {
        $arr[$prefix . '[custom_fields][' . $key . ']'] = $arr[$key];
      } else {
        $arr[$prefix . '[' . $key . ']'] = $arr[$key];
      }
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
