<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 13-8-14
 * Time: 18:18
 */

class RequestParamsTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \Autograph\Signable\RequestParams
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new \Autograph\Signable\RequestParams(array('q' => 'test'), 'authtest_');
    }

    public function testSignedParamsGetter()
    {
        $token = new \Autograph\Token('my_key', 'my_secret');
        $this->instance->sign($token);

        $params = $this->instance->getSignatureParams();

        $this->assertArrayHasKey('authtest_key', $params);
        $this->assertArrayHasKey('authtest_version', $params);
        $this->assertArrayHasKey('authtest_hash', $params);
        $this->assertArrayHasKey('authtest_timestamp', $params);
    }

    public function testSignatureFromParams()
    {
        $params = array(
            'authtest_key' => 'my_key',
            'authtest_version' => 'my_version',
            'authtest_hash' => 'my_hash',
            'authtest_timestamp' => time()
        );

        $this->instance = new \Autograph\Signable\RequestParams($params, 'authtest_');

        $signature = $this->instance->getSignature();

        $this->assertInstanceOf('Autograph\\Signature', $signature);
        $this->assertEquals($params, $this->instance->getSignatureParams());
    }

} 