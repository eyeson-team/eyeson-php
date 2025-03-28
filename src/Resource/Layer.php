<?php

namespace EyesonTeam\Eyeson\Resource;

/**
 * Layer Resource.
 **/
class Layer {
  private $api, $accessKey, $options;

  public function __construct($api, $accessKey) {
    $this->api = $api;
    $this->accessKey = $accessKey;
  }

  /**
   * Apply layer with options.
   *
   * @param array options
   * @return bool
   **/
  public function apply($options = []) {
    if (array_key_exists('insert', $options)) {
      trigger_error('"insert" will become deprecated soon', E_USER_WARNING);
    }
    $this->api->post('/rooms/' . $this->accessKey . '/layers', $options, false);
    return true;
  }

  /**
   * Apply layer with image url.
   *
   * @param string url public url of image
   * @param int zIndex 1:foreground, -1:background (optional, default 1)
   * @return bool
   **/
  public function setImageURL($url, $zIndex = 1) {
    $options = ['url' => $url, 'z-index' => $zIndex];
    $this->api->post('/rooms/' . $this->accessKey . '/layers', $options, false);
    return true;
  }

  /**
   * Apply layer with image file.
   *
   * @param string path local path of image file
   * @param int zIndex 1:foreground, -1:background (optional, default 1)
   * @return bool
   **/
  public function sendImageFile($path, $zIndex = 1) {
    $file = curl_file_create($path);
    $options = ['file' => $file, 'z-index' => $zIndex];
    $this->api->post('/rooms/' . $this->accessKey . '/layers', $options, false);
    return true;
  }

  /**
   * Apply text layer.
   *
   * @param string content
   * @param string title (optional)
   * @param string iconURL (optional) public URL of icon image
   * @return bool
   **/
  public function setText($content, $title = null, $iconURL = null) {
    trigger_error('setText will become deprecated soon', E_USER_WARNING);
    $options = ['content' => $content];
    if (!empty($title)) {
        $options['title'] = $title;
    }
    if (!empty($icon)) {
        $options['icon'] = $iconURL;
    }
    $this->api->post('/rooms/' . $this->accessKey . '/layers', ['insert' => $options], false);
    return true;
  }

  /**
   * Clear layer.
   *
   * @param int $zIndex (optional, default 1) 1:foreground, -1:background
   * @return bool stopped
   **/
  public function clear($zIndex = 1) {
    return $this->api->delete('/rooms/' . $this->accessKey . '/layers/' . $zIndex, 200, false);
  }
}

