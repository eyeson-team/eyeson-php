<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Webhook Resource.
 **/
class Webhook {
  const TYPES = ['user_update', 'document_update', 'recording_update',
    'broadcast_update', 'room_instance_update', 'team_update',
    'presentation_update'];

  private $api, $url, $type;

  public function __construct($api, $url, $type) {
    $this->api = $api;
    $this->url = $url;
    $this->type = $type;
  }

  /**
   * Save the webhook with current configuration.
   *
   * @return bool success?
   **/
  public function save() {
    if (!\in_array($this->type, self::TYPES)) {
      return false;
    }
    if (\filter_var($this->url, FILTER_VALIDATE_URL) === false) {
      return false;
    }
    $this->api->post('/webhooks', ['url' => $this->url, 'type' => $this->type]);
    return true;
  }
}
