<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Webhook Resource.
 **/
class Webhook {
  const TYPES = ['user_update', 'document_update', 'recording_update',
    'broadcast_update', 'room_update', 'team_update', 'presentation_update'];

  private $api, $url, $types;

  public function __construct($api, $url, array $types) {
    $this->api = $api;
    $this->url = $url;
    $this->types = $types;
  }

  /**
   * Save the webhook with current configuration.
   *
   * @return bool success?
   **/
  public function save() {
    if (\filter_var($this->url, FILTER_VALIDATE_URL) === false) {
      return false;
    }
    $types =  \implode(',', \array_intersect($this->types, self::TYPES));
    $this->api->post('/webhooks', ['url' => $this->url, 'types' => $types]);
    return true;
  }
}
