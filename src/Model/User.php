<?php

namespace EyesonTeam\Eyeson\Model;

/**
 * User
 **/
class User {
  private $data = [];

  /**
   * @param array $args with optional keys id, name and avatar
   **/
  public function __construct(array $args = []) {
    if (!empty($args)) {
      $this->fromArray($args);
    }
  }

  /**
   * Test user for validity, requires an identifier to be present.
   *
   * @return bool
   **/
  public function isValid() {
    return array_key_exists('id', $this->data) && !empty($this->data['id']);
  }

  /**
   * Build model from array.
   **/
  public function fromArray($args) {
    foreach ($args as $key => $value) {
      $this->data[$key] = $value;
    }
  }

  /**
   * Convert model to array.
   **/
  public function toArray() {
    return $this->data;
  }
}
