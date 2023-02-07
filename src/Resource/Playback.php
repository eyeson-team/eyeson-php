<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Playback Resource.
 * 
 * options:
 * - audio          Boolean Play audio (default: false)
 * - play_id        String	identifier, e.g. current timestamp or use a custom layout position identifier
 * - replacement_id String	user-id of the participants video to be replaced
 * - url            String	Hosted MP4/WEBM video file
 * - name           String	Custom readable name
 **/
class Playback {
  private $api, $accessKey, $options;

  public function __construct($api, $accessKey, $options = []) {
    $this->api = $api;
    $this->accessKey = $accessKey;
    $this->options = $options;
  }

  /**
   * Start playback.
   *
   * @return bool
   **/
  public function start() {
    if (empty($this->options['play_id'])) {
        $this->options['play_id'] = uniqid();
    }
    $this->api->post('/rooms/' . $this->accessKey . '/playbacks', ['playback' => $this->options], false);
    return true;
  }

  /**
   * Stop playback.
   *
   * @return bool
   **/
  public function stop() {
    if (empty($this->options['play_id'])) {
        return false;
    }
    return $this->api->delete('/rooms/' . $this->accessKey . '/playbacks/' . $this->options['play_id'], 200, false);
  }
}

