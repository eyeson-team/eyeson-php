<?php

namespace EyesonTeam\Eyeson\Model;

/**
 * User
 **/
class User {
  private $id, $name, $avatar;

  /**
   * @param array $args with optional keys id, name and avatar
   **/
  public function __construct(array $args = array()) {
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
    return !empty($id);
  }

  /**
   * Build model from array.
   **/
  public function fromArray($args) {
    foreach ($args as $key => $value) {
      $this->{$key} = $value;
    }
  }

  /**
   * Convert model to array.
   **/
  public function toArray() {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'avatar' => $this->avatar
    ];
  }
}
