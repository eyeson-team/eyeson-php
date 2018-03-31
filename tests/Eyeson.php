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
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team', 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }

  /**
   * @vcr join_room
   **/
  public function testJoinRoomWithArray() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $user = [ 'id' => 'mike@eyeson.team' ];
    $room = $eyeson->join($user, 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }

  /**
   * @vcr join_room
   **/
  public function testJoinRoomWithFullUser() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $user = [
      'id' => 'mike@eyeson.team',
      'name' => 'Mike',
      'avatar' => "https://www.gravatar.com/avatar/17ef9f5f544677fabb6bc5af1bbcc430"
    ];
    $room = $eyeson->join($user, 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }
}
