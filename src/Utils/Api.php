<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * eyeson Api request handling.
 **/
class Api {
  private $request;

  /**
   * @param string $endpoint
   * @param string $key
   **/
  public function __construct($endpoint, $key) {
    $this->request = new Request($endpoint, $key);
  }

  /**
   * Handle a HTTP POST request to eyeson API.
   *
   * @throws ...
   **/
  public function post($path, $params) {
    $response = $this->request->post($path, $params);
    $this->ensure($response);
    return $response->getBody();
  }

  /**
   * Ensure HTTP response status code is valid.
   **/
  private function ensure($response) {
    if ($response->getStatus() === 200) {
      return;
    }
    if ($response->getStatus() === 201) {
      return;
    }
    if ($response->getStatus() === 404) {
      // @TODO raise NotFoundError
    }
    if ($response->getStatus() === 401) {
      // @TODO raise AuthenticationError
    }
    // @TODO raise UnknownError
  }
}
