<?php

namespace EyesonTeam\Eyeson\Utils;

use EyesonTeam\Eyeson\Exception\AuthenticationError;
use EyesonTeam\Eyeson\Exception\BadRequestError;
use EyesonTeam\Eyeson\Exception\NotFoundError;
use EyesonTeam\Eyeson\Exception\GoneError;
use EyesonTeam\Eyeson\Exception\NetworkError;
use EyesonTeam\Eyeson\Exception\ForbiddenError;
use EyesonTeam\Eyeson\Exception\NotAcceptableError;
use EyesonTeam\Eyeson\Exception\UnknownError;

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
   * Handle a HTTP GET request to eyeson API.
   **/
  public function get($path, $requireAuth = true) {
    $response = $this->request->get($path, $requireAuth);
    $this->ensure($response);
    return $response->getBody();
  }

  /**
   * Handle a HTTP POST request to eyeson API.
   **/
  public function post($path, array $params = [], $requireAuth = true) {
    $response = $this->request->post($path, $params, $requireAuth);
    $this->ensure($response);
    return $response->getBody();
  }

  /**
   * Handle a HTTP PUT request to eyeson API.
   **/
  public function put($path, array $params = [], $requireAuth = true) {
    $response = $this->request->put($path, $params, $requireAuth);
    $this->ensure($response);
    return $response->getBody();
  }

  /**
   * Handle a HTTP DELETE request to eyeson API.
   *
   * @return boolean
   **/
  public function delete($path, $expectedStatus = 204, $requireAuth = true) {
    $response = $this->request->delete($path, $requireAuth);
    $this->ensure($response);
    return $response->getStatus() === $expectedStatus;
  }

  /**
   * Ensure HTTP response status code is valid.
   *
   * @throws NotFoundError on http status 404
   * @throws AuthenticationError on http status 401
   * @throws UnknownError on any unhandled http status
   **/
  private function ensure($response) {
    if ($response->getStatus() === 200) {
      return;
    }
    if ($response->getStatus() === 201) {
      return;
    }
    if ($response->getStatus() === 204) {
      return;
    }
    if ($response->getStatus() === 400) {
      throw new BadRequestError($this->errorOrGeneral($response,
        'Bad request detected. The resource you tried '
        . 'to update did receive invalid data. Please check the values '
        . 'provided and compare with the API documentation.'),
        $response->getStatus());
    }
    if ($response->getStatus() === 401) {
      throw new AuthenticationError($this->errorOrGeneral($response,
        'Authentication failed. You have no '
        . 'permission to request the resource. Please double check your '
        . 'secret api key. If you expect an error feel free to create an '
        . 'issue for the project anytime.'),
        $response->getStatus());
    }
    if ($response->getStatus() === 403) {
      throw new ForbiddenError($this->errorOrGeneral($response,
        'Authentication failed. You have no '
        . 'permission to request the resource. Please double check your '
        . 'secret api key. If you expect an error feel free to create an '
        . 'issue for the project anytime.'),
      $response->getStatus());
    }
    if ($response->getStatus() === 404) {
      throw new NotFoundError($this->errorOrGeneral($response,
        'Resource not found. The resource you requested '
        . 'does not exist or is expired. If you expect an error feel free to '
        . 'create an issue for the project anytime.'),
        $response->getStatus());
    }
    if ($response->getStatus() === 406) {
      throw new NotAcceptableError($this->errorOrGeneral($response,
      'Unable to process request.'),
      $response->getStatus());
    }
    if ($response->getStatus() === 410) {
      throw new GoneError($this->errorOrGeneral($response,
      'Resource is no longer available.'),
      $response->getStatus());
    }
    if ($response->getStatus() === 0) {
      throw new NetworkError('Network error. Please check your network '
        . 'connection and ensure the api target is reachable. If you receive '
        . 'this error in test-mode, you might have set a bad target api '
        . 'endpoint. Don\'t set a target URL in production.',
        $response->getStatus());
    }
    throw new UnknownError($this->errorOrGeneral($response,
      'Request failed. The request has failed for an '
      . 'unhandled reason. If you expect an error feel free to create an '
      . 'issue for the project anytime.'),
      $response->getStatus());
  }

  private function errorOrGeneral($response, $general) {
    if ($response->hasBody()) {
      $body = $response->getBody();
      if (isset($body['error'])) {
        return $body['error'];
      }
      if (isset($body['errors']) && is_array($body['errors']) && !empty($body['errors'][0])) {
        return implode(', ', $body['errors']);
      }
    }
    return $general;
  }
}
