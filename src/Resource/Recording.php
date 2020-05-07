<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Recording Resource.
 **/
class Recording {
  private $api, $accessKey, $active;

  public function __construct($api, $accessKey) {
    $this->api = $api;
    $this->accessKey = $accessKey;
    $this->active = false;
  }

  /**
   * Start a recording.
   *
   * @return boolean started
   **/
  public function start() {
    if ($this->active === true) {
      return false;
    }

    $this->api->post('/rooms/' . $this->accessKey . '/recording');
    return $this->active = true;
  }

  public function isActive() {
    return $this->active;
  }

  /**
   * Stop a recording.
   *
   * @return boolean stopped
   **/
  public function stop() {
    if ($this->active === false) {
      return false;
    }

    return !$this->active = !$this->api
      ->delete('/rooms/' . $this->accessKey . '/recording');
  }
}

