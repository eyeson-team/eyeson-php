<?php

require 'bootstrap.php';

use PHPUnit\Framework\TestCase;
use EyesonTeam\Eyeson\Eyeson;

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
    $user = [ 'name' => 'mike@eyeson.team' ];
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

  /**
   * @vcr add_webhook
   **/
  public function testAddWebhook() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $result = $eyeson->addWebhook('http://localhost:5678', 'recording_update');
    $this->assertSame($result, true);
  }
}
