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

  /**
   * Handle a post request.
   *
   * @param string $path
   * @param array $params
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function post($path, array $params = []) {
    return $this->deliver($path, 'POST', $params);
  }

  /**
   * Handle a delete request.
   *
   * @return EyesonTeam\Eyeson\Utils\Response
   **/
  public function delete($path) {
    return $this->deliver($path, 'DELETE');
  }

  private function deliver($path, $method = 'GET', array $params = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_URL, "$this->endpoint$path");
    $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D',
      http_build_query($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
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
