<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 12-8-14
 * Time: 0:03
 */

namespace Autograph;

/**
 * Class Token (Immutable)
 * @package Autograph
 */
class Token {

    /**
     * Identifier
     *
     * @var string
     */
    protected $key;

    /**
     * Secret
     *
     * @var string
     */
    protected $secret;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSecret()
    {
        return $this->secret;
    }

} 