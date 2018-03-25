<?php

// require __DIR__ . '/../src/Utils/Request.php';
require 'bootstrap.php';

use PHPUnit\Framework\TestCase;
use EyesonTeam\Eyeson\Eyeson;
use EyesonTeam\Eyeson\Utils\Request;

class EyesonTest extends TestCase {

  /**
   * @vcr join_room
   **/
  public function testJoinRoom() {
    $eyeson = new Eyeson('secret-key');
    $room = $eyeson->join('standup meeting', 'it\'s me, mario');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }
}
