<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * Handle HTTP request to the eyeson API.
 **/
class Request {
  private $endpoint, $apiKey;

  /**
   * @param string $endpoint
   * @param string $apiKey
   **/
  public function __construct($endpoint, $apiKey) {
    $this->endpoint = $endpoint;
    $this->apiKey = $apiKey;
  }

  public function hasApiKey() {
    return !empty($this->apiKey);
  }

  /**
   * Handle a get request.
   *
   * @param string $path
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function get($path, $requireAuth = true) {
    return $this->deliver($path, 'GET', [], $requireAuth);
  }

  /**
   * Handle a post request.
   *
   * @param string $path
   * @param array $params
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function post($path, array $params = [], $requireAuth = true) {
    return $this->deliver($path, 'POST', $params, $requireAuth);
  }

  /**
   * Handle a put request.
   *
   * @param string $path
   * @param array $params
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function put($path, array $params = [], $requireAuth = true) {
    return $this->deliver($path, 'PUT', $params, $requireAuth);
  }

  /**
   * Handle a delete request.
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function delete($path, $requireAuth) {
    return $this->deliver($path, 'DELETE', [], $requireAuth);
  }

  private function deliver($path, $method = 'GET', array $params = [], $requireAuth = true) {
    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_URL, "$this->endpoint$path");
    if ($method === 'POST' || $method === 'PUT') {
      if (array_key_exists('file', $params)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      } else {
        $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D',
          http_build_query($this->ensureBooleans($params)));
          curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
      }
    }
    if ($requireAuth && !empty($this->apiKey)) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: " . $this->apiKey
      ]);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'eyeson-php');
    $response = new Response();
    $response->setBody(curl_exec($ch));
    $response->setStatus(curl_getinfo($ch, CURLINFO_HTTP_CODE));
    curl_close($ch);

    return $response;
  }

  private function ensureBooleans($arr) {
    $fn = function($val) {
      if ($val === true) $val = 'true';
      if ($val === false) $val = 'false';
      return $val;
    };
    return array_map($fn, $arr);
  }
}
