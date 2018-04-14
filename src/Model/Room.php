<?php

namespace EyesonTeam\Eyeson\Model;

use EyesonTeam\Eyeson\Model\User;

/**
 * Room model received from eyeson service.
 **/
class Room {
  /**
   * Response data received from eyeson api service.
   **/
  private $data;

  public function __construct($data) {
    $this->data = $data;
  }

  /**
   * URL to eyeson web GUI.
   *
   * @return string
   **/
  public function getUrl() {
    return $this->data['links']['gui'];
  }

  /**
   * Guest URL to eyeson web GUI.
   *
   * @return string
   **/
  public function getGuestUrl() {
    return $this->data['links']['guest_join'];
  }

  /**
   * Get room options.
   *
   * @return array
   **/
  public function getOptions() {
    return $this->data['options'];
  }

  /**
   * Ready status. It may take a few seconds to setup user, room and the
   * meeting service infrastructure, though you don't have to wait for the room
   * to be ready. Redirect your user to the eyeson GUI that will take care for
   * the setup to be completed before joining the video conference.
   *
   * @return boolean room status
   **/
  public function isReady() {
    return $this->data['ready'];
  }

  /**
   * Check if room is expired.
   *
   * @return boolean expiration status
   **/
  public function isExpired() {
    return $this->data['room']['shutdown'];
  }

  /**
   * Room identifer.
   *
   * @return string room identifer
   **/
  public function getId() {
    return $this->data['room']['id'];
  }

  /**
   * Room name.
   *
   * @return string room name
   **/
  public function getName() {
    return $this->data['room']['name'];
  }

  /**
   * Access Key for the room + user combination.
   *
   * @return string room + user access key
   **/
  public function getAccessKey() {
    return $this->data['access_key'];
  }

  /**
   * Guest token for the room.
   *
   * @return string guest token
   **/
  public function getGuestToken() {
    return $this->data['room']['guest_token'];
  }
}
