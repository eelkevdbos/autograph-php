<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 13-8-14
 * Time: 17:35
 */

require_once __DIR__ . '/mocks/MockSignable.php';

class AutographTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->instance = new \Autograph\Autograph();
    }

    public function testTokenGeneration()
    {
        $token = $this->instance->token('my_key', 'my_secret');
        $this->assertInstanceOf('Autograph\\Token', $token);
    }

    public function testValidation()
    {
        //create a token
        $token = $this->instance->token('my_key', 'my_secret');

        //create a signable, and sign it with the token
        $signable = new MockSignable();
        $signable->sign($token);

        //validate against the same token
        $authenticated = $this->instance->validate($signable, $token);

        $this->assertTrue($authenticated);
    }

} 