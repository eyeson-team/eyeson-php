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
   * Handle post request.
   *
   * @param string $path
   * @param array $params
   **/
  public function post($path, array $params) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, "$this->endpoint$path");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: " . $this->apiKey
    ]);
    $return = curl_exec($ch);
    $split = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    return $this->buildResponse($return, $split);
  }

  private function buildResponse($return, $split) {
    $response = new Response();
    $response->setHeader(\substr($return, 0, $split));
    $response->setBody(\substr($return, $split));
    return $response;
  }
}
