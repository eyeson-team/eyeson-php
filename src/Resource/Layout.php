<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Layout Resource.
 **/
class Layout {
  private $api, $accessKey;

  public function __construct($api, $accessKey) {
    $this->api = $api;
    $this->accessKey = $accessKey;
  }

  /**
   * Apply layout options.
   *
   * @param array options
   * @return bool
   **/
  public function apply($options) {
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $options, false);
    return true;
  }

  /**
   * Update video podium.
   *
   * @param array userList a list of user IDs or empty strings
   * @return bool
   **/
  public function update($userList) {
    $params = ['layout' => 'custom', 'users' => $userList];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params, false);
    return true;
  }

  /**
   * Use automatic layout updates.
   *
   * @return bool
   **/
  public function useAuto() {
    $params = ['layout' => 'auto'];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params, false);
    return true;
  }

  /**
   * Hide names in video podium.
   *
   * @return bool
   **/
  public function hideNames() {
    $params = ['show_names' => false];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params, false);
    return true;
  }

  /**
   * Show names in video podium.
   *
   * @return bool
   **/
  public function showNames() {
    $params = ['show_names' => true];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params, false);
    return true;
  }
}

