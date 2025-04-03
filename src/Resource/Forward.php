<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Forward Resource.
 **/
class Forward {
  private $api, $roomId;

  public function __construct($api, $roomId) {
    $this->api = $api;
    $this->roomId = $roomId;
  }

  /**
   * Start source forward
   *
   * @param string forwardId - Custom, unique identifier
   * @param string userId - Custom identifier for participants or video sources
   * @param string|array type - 'audio', 'video', 'audio,video'
   * @param string url - The WHIP endpoint URL
   * @return bool
   **/
  public function source($forwardId, $userId, $type, $url) {
    $params = ['forward_id' => $forwardId, 'user_id' => $userId, 'type' => $type, 'url' => $url];
    $this->api->post('/rooms/' . $this->roomId . '/forward/source', $params, true);
    return true;
  }

  /**
   * Start MCU One View forward
   *
   * @param string forwardId - Custom, unique identifier
   * @param string|array type - 'audio', 'video', 'audio,video'
   * @param string url - The WHIP endpoint URL
   * @return bool
   **/
  public function mcu($forwardId, $type, $url) {
    $params = ['forward_id' => $forwardId, 'type' => $type, 'url' => $url];
    $this->api->post('/rooms/' . $this->roomId . '/forward/mcu', $params, true);
    return true;
  }

  /**
   * Start playback forward
   *
   * @param string forwardId - Custom, unique identifier
   * @param string playId - Custom identifier for playbacks
   * @param string|array type - 'audio', 'video', 'audio,video'
   * @param string url - The WHIP endpoint URL
   * @return bool
   **/
  public function playback($forwardId, $playId, $type, $url) {
    $params = ['forward_id' => $forwardId, 'play_id' => $playId, 'type' => $type, 'url' => $url];
    $this->api->post('/rooms/' . $this->roomId . '/forward/playback', $params, true);
    return true;
  }

  /**
   * Stop an active forward
   *
   * @param string forwardId - Custom, unique identifier
   * @return bool stopped
   **/
  public function stop($forwardId) {
    return $this->api->delete('/rooms/' . $this->roomId . '/forward/' . $forwardId, 200);
  }
}
