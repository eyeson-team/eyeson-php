<?php

require 'bootstrap.php';

use PHPUnit\Framework\TestCase;
use EyesonTeam\Eyeson\Eyeson;
use EyesonTeam\Eyeson\Exception\AuthenticationError;
use EyesonTeam\Eyeson\Exception\UnknownError;

class ExceptionsTest extends TestCase {

  /**
   * @vcr exceptions
   **/
  public function testInvalidApiKey() {
    $eyeson = new Eyeson('invalid-key', 'http://localhost:8000');
    $this->expectException(AuthenticationError::class);
    $eyeson->join('mike@eyeson.team');
  }

  /**
   * @vcr exceptions
   **/
  public function testUnknownError() {
    $eyeson = new Eyeson('secret-key', 'http://localhost:8000');
    $this->expectException(UnknownError::class);
    $eyeson->join('mike@eyeson.team');
  }
}
