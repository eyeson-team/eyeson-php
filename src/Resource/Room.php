<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Model\Room as RoomModel;
use EyesonTeam\Eyeson\Exception\TimeoutError;

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
  const OPTIONS = ['show_names', 'show_label', 'exit_url',
    'recording_available', 'broadcast_available', 'layout_available',
    'lock_available', 'kick_available', 'sfu_mode',
    'reaction_available', 'guest_token_available',
    'background_color', 'widescreen', 'custom_fields'];
  const CUSTOM_OPTIONS = ['logo', 'locale', 'hide_chat', 'virtual_background',
    'virtual_background_allow_guest', 'virtual_background_image',
    'virtual_background_allow_local_image', 'iframe_postmessage_origin'];

  public function __construct($api, $id) {
    $this->id = $id;
    $this->api = $api;
  }

  /**
   * Join the room.
   *
   * @param Eyeson\Model\User user
   * @return Eyeson\Model\Room
   **/
  public function join(User $user) {
    $params = $this->toArray($user);
    return new RoomModel($this->api->post('/rooms', $params));
  }

  /**
   * Set room name
   *
   * @param string name
   * @return Eyeson\Resource\Room
   **/
  public function setName(string $name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Set room options.
   *
   * @param array $options see Room::OPTIONS and Room:CUSTOM_OPTIONS
   * @return Eyeson\Resource\Room
   **/
  public function setOptions(array $options) {
    $this->options = \array_filter($options, function($key) {
      return \in_array($key, self::OPTIONS) or \in_array($key, self::CUSTOM_OPTIONS);
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
   * Fetch room data until room is ready.
   *
   * @param string accessKey
   * @return Eyeson\Model\Room
   * @throws TimeoutError after 30s
   **/
  public function waitReady($accessKey) {
    $timeout = time() + 30;
    $room = new RoomModel($this->api->get('/rooms/' . $accessKey, false));
    while (!$room->isReady()) {
      if (time() > $timeout) {
        throw new TimeoutError('Room ready timeout.');
      }
      sleep(1);
      $room = new RoomModel($this->api->get('/rooms/' . $accessKey, false));
    }
    return $room;
  }

  /**
   * To array
   * @param Eyeson\Model\User user (optional)
   * @return array
   */
  public function toArray($user = null) {
    $params = \array_merge(
      [ "id" => $this->id, "name" => $this->name ],
      $this->prefix($this->options, 'options')
    );
    if ($user) {
      $params = \array_merge($this->prefix($user->toArray(), 'user'), $params);
    }
    return $params;
  }

  /**
   * Force to stop a running meeting.
   *
   * @return bool
   **/
  public function destroy() {
    return $this->api->delete('/rooms/' . $this->id);
  }
}
