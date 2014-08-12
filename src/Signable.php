<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 12-8-14
 * Time: 18:03
 */

namespace Autograph;

use DateTime;

trait Signable
{

    /**
     * @var Signature
     */
    protected $signature;

    /**
     * Getter for Signature
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Setter for Signature
     * @param Signature $signature
     */
    public function setSignature(Signature $signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * Algorithm used to sign
     * @return string
     */
    public function getSignatureAlgorithm()
    {
        return 'sha256';
    }

    /**
     * Sign the object
     * @param Token $token
     */
    public function sign(Token $token, $timestamp = null, $version = null)
    {
        $signature = $this->createSignature(
            $token->getKey(),
            $timestamp ?: time(),
            $version ?: '1.0'
        );

        $this->setSignature($signature);

        $signature->setHash(hash_hmac(
            $this->getSignatureAlgorithm(),
            $token->getSecret(),
            serialize($this)
        ));

        return $this;
    }

    /**
     * Factorize signature
     * @param $key
     * @param $timestamp
     * @param $version
     * @return Signature
     */
    protected function createSignature($key, $timestamp, $version, $hash = null)
    {
        return new Signature($key, $timestamp, $version, $hash);
    }

} 