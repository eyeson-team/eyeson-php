<?php

// use the same namespace to override curl functions
namespace EyesonTeam\Eyeson\Utils;

use PHPUnit\Framework\TestCase;
use EyesonTeam\Eyeson\Eyeson;

$mockCh = null;
$mockResult = [];
$mockStatus = 200;

class MockCurlHandle {
    public array $options = [];
    public function getPostFields() {
      parse_str($this->options[CURLOPT_POSTFIELDS], $fields);
      return $fields;
    }
}

function curl_init() {
  global $mockCh;
  $mockCh = new MockCurlHandle();
  return $mockCh;
}

function curl_setopt($ch, $option, $value) {
    $ch->options[$option] = $value;
    return true;
}
function curl_exec($ch) {
  global $mockResult;
  return json_encode($mockResult);
}

function curl_getinfo($ch, $option = null) {
    global $mockStatus;
    return $mockStatus;
}

function curl_close($ch) {
    // do nothing
}

class EyesonTest extends TestCase {

  public function testJoinRoom() {
    global $mockResult, $mockStatus;
    $mockResult = ['links' => ['gui' => 'https://app.eyeson.team/?testtoken']];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team', 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }

  public function testJoinRoomWithArray() {
    global $mockResult, $mockStatus;
    $mockResult = ['links' => ['gui' => 'https://app.eyeson.team/?testtoken']];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $user = [ 'name' => 'mike@eyeson.team' ];
    $room = $eyeson->join($user, 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }

  public function testJoinRoomWithFullUser() {
    global $mockResult, $mockStatus;
    $mockResult = ['links' => ['gui' => 'https://app.eyeson.team/?testtoken']];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $user = [
      'id' => 'mike@eyeson.team',
      'name' => 'Mike',
      'avatar' => "https://www.gravatar.com/avatar/17ef9f5f544677fabb6bc5af1bbcc430"
    ];
    $room = $eyeson->join($user, 'standup meeting');
    $this->assertSame($room->getUrl(), 'https://app.eyeson.team/?testtoken');
  }

  public function testJoinRoomWithOptions() {
    global $mockResult, $mockStatus;
    $mockResult = ['options' => [
      'show_names' => false,
      'show_label' => false,
      'exit_url' => 'https://www.eyeson.team/',
      'recording_available' => false,
      'broadcast_available' => false,
      'layout_available' => false,
      'layout_users' => null,
      'custom_fields' => ['logo' => 'https://www.eyeson.com/logo.png']
    ]];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $options = [
      'show_names' => false,
      'show_label' => false,
      'exit_url' => 'https://www.eyeson.team/',
      'recording_available' => false,
      'broadcast_available' => false,
      'layout_available' => false,
      'logo' => 'https://www.eyeson.com/logo.png'
    ];
    $room = $eyeson->join('mike@eyeson.team', null, $options);
    $extra = [
      'layout_users' => null,
      'custom_fields' => ['logo' => $options['logo']]
    ];
    unset($options['logo']);
    $this->assertSame($room->getOptions(), \array_merge($options, $extra));
  }

  public function testJoinRoomWithLocale() {
    global $mockResult, $mockStatus;
    $mockResult = ['options' => [
      'custom_fields' => ['locale' => 'de']
    ]];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $options = ['locale' => 'de'];
    $room = $eyeson->join('mike@eyeson.team', null, $options);
    $extra = ['custom_fields' => ['locale' => 'de']];
    $this->assertSame($room->getOptions(), $extra);
  }

  public function testProvidesRoomResponse() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'access_key' => '<access-key>',
      'ready' => false,
      'room' => [
        'id' => 'fourtytwo',
        'name' => 'team mike',
        'guest_token' => 'seventeen',
        'shutdown' => false,
      ],
      'links' => [
        'guest_join' => 'https://app.eyeson.team/?guest=twentytwo'
      ]
    ];
    $mockStatus = 200;
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

  public function testSetRoomName() {
    global $mockCh, $mockResult, $mockStatus;
    $mockResult = [
      'room' => [
        'name' => 'Standup',
      ],
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team', null, ['name' => 'Standup']);
    $fields = $mockCh->getPostFields();
    $this->assertSame($fields['name'], 'Standup');
    $this->assertSame($room->getName(), 'Standup');
  }

  public function testAddWebhook() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $result = $eyeson->addWebhook('http://localhost:5678', 'room_update,recording_update');
    $this->assertSame($result, true);
  }

  public function testStopRoom() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'room' => [
        'id' => 'Standup',
      ],
    ];
    $mockStatus = 204;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->shutdown($room));
  }

  public function testRecordRoom() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $rec = $eyeson->record($room);
    $this->assertTrue($rec->start());
  }

  public function testStopRecording() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $rec = $eyeson->record($room);
    $this->assertTrue($rec->stop());
  }

  public function testAutoLayout() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $this->assertTrue($eyeson->layout($room)->useAuto());
  }

  public function testCustomLayout() {
    global $mockResult, $mockStatus;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $layout = $eyeson->layout($room);
    $this->assertTrue($layout->update(['idone', 'idtwo']));
  }

  public function testShowNames() {
    global $mockResult, $mockStatus, $mockCh;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $result = $eyeson->layout($room)->showNames();
    $fields = $mockCh->getPostFields();
    $this->assertSame($fields['show_names'], 'true');
    $this->assertTrue($result);
  }

  public function testHideNames() {
    global $mockResult, $mockStatus, $mockCh;
    $mockResult = [
      'access_key' => '<access-key>'
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team');
    $result = $eyeson->layout($room)->hideNames();
    $fields = $mockCh->getPostFields();
    $this->assertSame($fields['show_names'], 'false');
    $this->assertTrue($result);
  }

  public function testVirtualBackground() {
    global $mockResult, $mockStatus, $mockCh;
    $mockResult = [
      'options' => [
        'custom_fields' => [
          'virtual_background' => true
        ]
      ]
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team', null, [
      'virtual_background' => true,
      'virtual_background_allow_guest' => true,
      'virtual_background_image' => 'http://localhost:8000/bg.jpg',
      'virtual_background_allow_local_image' => true
    ]);
    $fields = $mockCh->getPostFields();
    $this->assertSame($fields['options']['custom_fields']['virtual_background'], 'true');
    $this->assertSame($fields['options']['custom_fields']['virtual_background_image'], 'http://localhost:8000/bg.jpg');
    $this->assertTrue($room->getOptions()['custom_fields']['virtual_background']);
  }

  public function testSFUMode() {
    global $mockResult, $mockStatus, $mockCh;
    $mockResult = [
      'options' => [
        'sfu_mode' => 'disabled'
      ]
    ];
    $mockStatus = 200;
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $room = $eyeson->join('mike@eyeson.team', null, ['sfu_mode' => 'disabled']);
    $fields = $mockCh->getPostFields();
    $this->assertSame($fields['options']['sfu_mode'], 'disabled');
    $this->assertEquals($room->getOptions()['sfu_mode'], 'disabled');
  }
}
