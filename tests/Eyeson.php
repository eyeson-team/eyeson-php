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
   * @vcr room_options
   **/
  public function testJoinRoomWithOptions() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $options = [
      'show_names' => false,
      'show_label' => false,
      'exit_url' => 'https://www.eyeson.team/',
      'recording_available' => false,
      'broadcast_available' => false,
      'layout_available' => false
    ];
    $room = $eyeson->join('mike@eyeson.team', null, $options);
    $extra = ['layout_users' => null, 'custom_fields' => null];
    $this->assertSame($room->getOptions(), \array_merge($options, $extra));
  }

  /**
   * @vcr room_response
   **/
  public function testProvidesRoomResponse() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertSame($room->isReady(), false);
    $this->assertSame($room->isExpired(), false);
    $this->assertSame($room->getId(), 'fourtytwo');
    $this->assertSame($room->getName(), 'team mike');
    $this->assertSame($room->getAccessKey(), '<access-key>');
    $this->assertSame($room->getGuestToken(), 'seventeen');
    $this->assertSame($room->getGuestUrl(), 'https://app.eyeson.team/?guest=twentytwo');
  }

  /**
   * @vcr add_webhook
   **/
  public function testAddWebhook() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $result = $eyeson->addWebhook('http://localhost:5678',
      'room_update,recording_update');
    $this->assertSame($result, true);
  }

  /**
   * @vcr stop_room
   **/
  public function testStopRoom() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->shutdown($room));
  }

  /**
   * @vcr record_room
   **/
  public function testRecordRoom() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->record($room)->isActive());
  }

  /**
   * @vcr stop_recording
   **/
  public function testStopRecording() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $rec = $eyeson->record($room);
    $this->assertTrue($rec->stop());
  }

  /**
   * @vcr layout_auto
   **/
  public function testAutoLayout() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->getLayout($room)->useAuto());
  }

  /**
   * @vcr layout_users_update
   **/
  public function testCustomLayout() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $layout = $eyeson->getLayout($room);
    $this->assertTrue($layout->update(['idone', 'idtwo']));
  }

  /**
   * @vcr layout_hide_names
   **/
  public function testHideNames() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->getLayout($room)->hideNames());
  }

  /**
   * @vcr layout_show_names
   **/
  public function testShowNames() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->getLayout($room)->showNames());
  }
}
