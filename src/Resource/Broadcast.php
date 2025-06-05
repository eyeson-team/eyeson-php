<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Broadcast Resource.
 * 
 * options:
 * - stream_url
 * - player_url
 **/
class Broadcast {
  private $api, $accessKey, $options;

  public function __construct($api, $accessKey, $options = []) {
    $this->api = $api;
    $this->accessKey = $accessKey;
    $this->options = $options;
  }

  /**
   * Check if broadcast is currently active.
   *
   * @return bool
   **/
  public function isActive() {
    $data = $this->api->get('/rooms/' . $this->accessKey, false);
    return count($data['broadcasts']) > 0;
  }

  /**
   * Start a recording.
   *
   * @return bool
   **/
  public function start() {
    if (empty($this->options['stream_url'])) {
        return false;
    }
    $this->api->post('/rooms/' . $this->accessKey . '/broadcasts', $this->options, false);
    return true;
  }

  /**
   * Stop a recording.
   *
   * @return bool
   **/
  public function stop() {
    return $this->api->delete('/rooms/' . $this->accessKey . '/broadcasts', 200, false);
  }
}
