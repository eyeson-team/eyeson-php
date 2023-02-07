<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Webhook Resource.
 **/
class Webhook {
  const TYPES = [
    'room_update', 'recording_update', 'participant_update', 'snapshot_update'
  ];

  private $api, $url, $types;

  public function __construct($api, $url, array $types) {
    $this->api = $api;
    $this->url = $url;
    $this->types = $types;
  }

  /**
   * Save the webhook with current configuration.
   *
   * @return bool
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
