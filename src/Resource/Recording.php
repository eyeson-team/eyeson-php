<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Recording Resource.
 **/
class Recording {
  private $api, $accessKey;

  public function __construct($api, $accessKey) {
    $this->api = $api;
    $this->accessKey = $accessKey;
  }

  /**
   * Start a recording.
   *
   * @return bool
   **/
  public function start() {
    $this->api->post('/rooms/' . $this->accessKey . '/recording', [], false);
    return true;
  }

  /**
   * Stop a recording.
   *
   * @return bool
   **/
  public function stop() {
    return $this->api->delete('/rooms/' . $this->accessKey . '/recording', 200, false);
  }
}

