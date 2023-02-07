<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Message Resource.
 **/
class Message {
  private $api, $accessKey, $type, $content;

  public function __construct($api, $accessKey, $type, $content) {
    $this->api = $api;
    $this->accessKey = $accessKey;
    $this->type = $type;
    $this->content = $content;
  }

  /**
   * Send message
   *
   * @return bool
   **/
  public function send() {
    $this->api->post('/rooms/' . $this->accessKey . '/messages', [
        'type' => $this->type,
        'content' => $this->content
    ], false);
    return true;
  }
}
