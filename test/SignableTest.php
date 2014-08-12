<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 13-8-14
 * Time: 17:45
 */

require_once __DIR__ . '/mocks/MockSignable.php';

class SignableTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->instance = new MockSignable();
    }

    public function testSettersAndGetters()
    {
        $this->instance->setSignature($signature = new \Autograph\Signature('my_key', new DateTimeImmutable(), '1.0'));
        $this->assertEquals($this->instance->getSignature(), $signature);
    }

    public function testSigning()
    {
        $this->instance->sign(new \Autograph\Token('my_key', 'my_secret'));

        //test if a signature was set
        $this->assertInstanceOf('Autograph\\Signature',$signature = $this->instance->getSignature());

        //test if the signature contains a hash
        $this->assertNotNull($signature->hash);
    }

} 