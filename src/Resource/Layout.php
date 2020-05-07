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
   * Update video podium.
   *
   * @param array $userList a list of user IDs or empty strings
   * @return boolean changed
   **/
  public function update($userList) {
    $params = ['layout' => 'custom', 'users' => $userList];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params);
    return true;
  }

  /**
   * Use automatic layout updates.
   *
   * @return boolean changed
   **/
  public function useAuto() {
    $params = ['layout' => 'auto'];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params);
    return true;
  }

  /**
   * Hide names in video podium.
   *
   * @return boolean changed
   **/
  public function hideNames() {
    $params = ['show_names' => false];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params);
    return true;
  }

  /**
   * Show names in video podium.
   *
   * @return boolean changed
   **/
  public function showNames() {
    $params = ['show_names' => true];
    $this->api->post('/rooms/' . $this->accessKey . '/layout', $params);
    return true;
  }
}

