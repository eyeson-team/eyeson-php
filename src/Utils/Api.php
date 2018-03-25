<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * eyeson Api
 **/
class Api {
  private $request;

  public function __construct($endpoint, $key) {
    $this->request = new Request($endpoint, $key);
  }

  public function post($path, $params) {
    $response = $this->request->post($path, $params);
    // TODO handle forbidden access, timeout, not reachable
    return json_decode($response, true);
  }
}
