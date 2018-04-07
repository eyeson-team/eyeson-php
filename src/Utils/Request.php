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
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, "$this->endpoint$path");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: " . $this->apiKey
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'eyeson-php');
    $response = new Response();
    $response->setBody(curl_exec($ch));
    $response->setStatus(curl_getinfo($ch, CURLINFO_HTTP_CODE));
    curl_close($ch);

    return $response;
  }
}
