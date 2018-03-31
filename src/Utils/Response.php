<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * Handle HTTP Request to the eyeson api.
 **/
class Response {
  private $status, $body;

  /**
   * Set header, exclusive the status code.
   **/
  public function setHeader($header) {
    $code = \explode(\explode($header, "\n")[0], ' ')[1];
    $this->status = \intval($code);
  }

  /**
   * @return int $status http status code
   **/
  public function getStatus() {
    return $this->status;
  }

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
