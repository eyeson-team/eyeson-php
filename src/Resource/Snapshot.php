<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Snapshot Resource.
 **/
class Snapshot {
  private $api, $accessKey;

  public function __construct($api, $accessKey) {
    $this->api = $api;
    $this->accessKey = $accessKey;
  }

  /**
   * Create snapshot.
   *
   * @return bool
   **/
  public function create() {
    $this->api->post('/rooms/' . $this->accessKey . '/snapshot', [], false);
    return true;
  }

  /**
   * Get snapshot by accessKey and snapshotId.
   *
   * @return object snapshot
   **/
  public function getByAccessKey($snapshotId) {
    return $this->api->get('/rooms/' . $this->accessKey . '/snapshots/' . $snapshotId);
  }

  /**
   * Get snapshot by snapshotId.
   *
   * @return object snapshot
   **/
  public function getById($snapshotId) {
    return $this->api->get('/snapshots/' . $snapshotId);
  }

  /**
   * Delete snapshot by snapshotId.
   *
   * @return bool
   **/
  public function delete($snapshotId) {
    return $this->api->delete('/snapshots/' . $snapshotId, 200);
  }
}

