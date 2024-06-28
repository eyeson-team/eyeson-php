<?php

namespace EyesonTeam\Eyeson\Model;

/**
 * Permalink model received from eyeson service.
 **/
class Permalink {
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
   * Get data.
   *
   * @return array data
   **/
  public function getData() {
    return $this->data;
  }

  /**
   * Permalink identifer.
   *
   * @return string permalink identifer
   **/
  public function getId() {
    return $this->data['permalink']['id'];
  }

  /**
   * User token to start a meeting.
   *
   * @return string user token
   **/
  public function getUserToken() {
    return $this->data['permalink']['user_token'];
  }

  /**
   * Guest token for the meeting.
   *
   * @return string guest token
   **/
  public function getGuestToken() {
    return $this->data['permalink']['guest_token'];
  }

  /**
   * Room started status.
   *
   * @return boolean room started status
   **/
  public function isStarted() {
    return $this->data['room']['started_at'] !== null;
  }
}
