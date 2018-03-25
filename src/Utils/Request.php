<?php

namespace EyesonTeam\Eyeson\Utils;

/**
 * Handle HTTP Request to the eyeson api.
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

  /**
   * @param string $path
   * @param array $params
   **/
  public function post($path, array $params) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, "$this->endpoint$path");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ "Authorization: Basic "
      . base64_encode($this->apiKey) ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}
