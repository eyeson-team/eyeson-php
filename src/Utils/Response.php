<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * Handle HTTP Request to the eyeson api.
 **/
class Response {
  private $status, $body;

  /**
   * Set HTTP status code.
   **/
  public function setStatus($code) {
    $this->status = \intval($code);
  }

  /**
   * @return int $status HTTP status code
   **/
  public function getStatus() {
    return $this->status;
  }

  /**
   * @return boolean HTTP status
   **/
  public function isOk() {
    return $this->status >= 200 && $this->status < 300;
  }

  /**
   * @param string $body json encoded response
   **/
  public function setBody($body) {
    $this->body = $body;
  }

  /**
   * @return array $body json decoded response body
   **/
  public function getBody() {
    return \json_decode($this->body, true);
  }
}
