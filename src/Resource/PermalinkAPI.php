<?php

namespace EyesonTeam\Eyeson\Resource;

use EyesonTeam\Eyeson\Model\User;
use EyesonTeam\Eyeson\Model\Permalink;
use EyesonTeam\Eyeson\Model\Room as Response;
use EyesonTeam\Eyeson\Resource\Room;

class PermalinkAPI {
    
  private $api;

  public function __construct($api) {
    $this->api = $api;
  }

  /**
   * Create permalink entry
   *
   * @param mixed $user provide an string(id), array or Eyeson\Model\User
   * @param array options (optional)
   * @return Eyeson\Model\Permalink
   **/
  public function create($user, array $options = []) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    $room = new Room($this->api, null);
    if(\array_key_exists('name', $options)) {
      $room->setName($options['name']);
      unset($options['name']);
    }
    $room->setOptions($options);
    $params = $room->toArray($user);
    if (\array_key_exists('expires_at', $options)) {
      $params['expires_at'] = $options['expires_at'];
    }

    $data = $this->api->post('/permalink', $params);
    return new Permalink($data);
  }

  /**
   * Get permalink entry by Id
   *
   * @param string permalinkId
   * @return Eyeson\Model\Permalink
   **/
  public function getById($permalinkId) {
    $data = $this->api->get('/permalink/' . $permalinkId);
    return new Permalink($data);
  }

  /**
   * Get all permalink entries
   *
   * @param array options [number page, number limit, boolean expired]
   * @return array
   **/
  public function getAll(array $options = []) {
    $query = '?';
    $query .= 'page=' . (\array_key_exists('page', $options) ? $options['page'] : '1');
    $query .= '&limit=' . (\array_key_exists('limit', $options) ? $options['limit'] : '25');
    if (\array_key_exists('expired', $options)) {
      $query .= '&expired=' . ($options['expired'] ? 'true' : 'false');
    }
    $data = $this->api->get('/permalink' . $query);
    $items = [];
    foreach ($data['items'] as $item) {
      \array_push($items, new Permalink($item));
    }
    $data['items'] = $items;
    return $data;
  }

  /**
   * Update permalink entry
   *
   * @param string permalinkId
   * @param array options (optional)
   * @return Eyeson\Model\Permalink
   **/
  public function update($permalinkId, array $options = []) {
    $room = new Room($this->api, null);
    if(\array_key_exists('name', $options)) {
      $room->setName($options['name']);
      unset($options['name']);
    }
    $room->setOptions($options);
    $params = $room->toArray();
    if (\array_key_exists('expires_at', $options)) {
      $params['expires_at'] = $options['expires_at'];
    }
    $data = $this->api->put('/permalink/' . $permalinkId, $params);
    return new Permalink($data);
  }

  /**
   * Add user to permalink entry
   *
   * @param string permalinkId
   * @param mixed $user provide an string(id), array or Eyeson\Model\User
   * @param array options (optional)
   * @return Eyeson\Model\Permalink
   **/
  public function addUser($permalinkId, $user, array $options = []) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    $user->fromArray($options);
    $params = ['user' => $user->toArray()];
    $data = $this->api->post('/permalink/' . $permalinkId . '/users', $params);
    return new Permalink($data);
  }

  /**
   * Remove user from permalink entry
   *
   * @param string permalinkId
   * @param string userToken
   * @return boolean
   **/
  public function removeUser($permalinkId, $userToken) {
    return $this->api->delete('/permalink/' . $permalinkId . '/users/' . $userToken, 200);
  }

  /**
   * Start or join meeting from permalink
   *
   * @param string userToken
   * @return Eyeson\Model\Room
   **/
  public function joinMeeting($userToken) {
    $data = $this->api->post('/permalink/' . $userToken, [], false);
    $room = new Response($data);
    return $room;
  }

  /**
   * Register guest user with permalink guest token
   *
   * @param mixed $user provide an string(id), array or Eyeson\Model\User
   * @param string guestToken
   * @param array options (optional)
   * @return Eyeson\Model\Room
   **/
  public function registerGuest($user, $guestToken, array $options = []) {
    if (\is_string($user)) {
      $user = new User(['name' => $user]);
    }
    elseif (\is_array($user)) {
      $user = new User($user);
    }
    $user->fromArray($options);
    $data = $this->api->post('/guests/' . $guestToken, $user->toArray(), false);
    $room = new Response($data);
    return $room;
  }

  /**
   * Delete permalink entry
   *
   * @param string permalinkId
   * @return boolean
   **/
  public function delete($permalinkId) {
    return $this->api->delete('/permalink/' . $permalinkId, 200);
  }
}
