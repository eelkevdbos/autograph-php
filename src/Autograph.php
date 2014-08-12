<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 11-8-14
 * Time: 23:05
 */

namespace Autograph;

class Autograph {

    /**
     * Factory method for the creation of a token
     * @param string $key
     * @param string $secret
     * @return Token
     */
    public function token($key, $secret)
    {
        return new Token($key, $secret);
    }

    /**
     * Validate given signable against a reference token
     * @param $signable
     * @param Token $token
     * @param int $timestampGrace
     * @return bool
     * @throws \BadMethodCallException
     */
    public function validate($signable, Token $token, $timestampGrace = 600)
    {
        //ductyping signable trait
        if(!method_exists($signable, 'getSignature')) {
            throw new \BadMethodCallException("Method getSignature not found on signable");
        }

        $signature = $signable->getSignature();
        if($signature->key != $token->getKey()) {
            throw new \InvalidArgumentException("Signature keys do not match");
        }

        //resign the object with supplied reference token
        $referenceHash = $signature->hash;
        $signable->sign($token, $signature->timestamp, $signature->version);

        if($referenceHash != $signature->hash) {
            throw new \InvalidArgumentException("Signature hashes do not match");
        }

        return true;
    }
} 