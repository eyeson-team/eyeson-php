<?php

require 'bootstrap.php';

use PHPUnit\Framework\TestCase;
use EyesonTeam\Eyeson\Eyeson;
use EyesonTeam\Eyeson\Exception;

class ExceptionsTest extends TestCase {

  /**
   * @vcr exceptions
   **/
  public function testInvalidApiKey() {
    $eyeson = new Eyeson('invalid-key', 'http://localhost:8000');
    $this->expectException(Exception\AuthenticationError::class);
    $eyeson->join('mike@eyeson.team');
  }

  public function testInvalidApiTarget() {
    $eyeson = new Eyeson('secret-key', 'brokenapitarget');
    $this->expectException(Exception\NetworkError::class);
    $eyeson->join('mike@eyeson.team');
  }

  /**
   * @vcr exceptions
   **/
  public function testUnknownError() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $this->expectException(Exception\UnknownError::class);
    $eyeson->join('mike@eyeson.team');
  }
}
