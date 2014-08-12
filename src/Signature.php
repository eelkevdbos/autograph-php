<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 12-8-14
 * Time: 2:16
 */

namespace Autograph;

/**
 * Class Signature
 *
 * Attaches to an object and contains the hash of a serialized object
 */
class Signature {

    protected $properties;

    public function __construct($key, $timestamp, $version, $hash = null)
    {
        $this->properties = compact('key', 'timestamp', 'version', 'hash');
    }

    /**
     * Magic getter for signature properties
     * @param $property
     * @return mixed|null
     */
    public function __get($property)
    {
        if(array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }
        return null;
    }

    /**
     * Setter for hash
     * @param $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->properties['hash'] = $hash;
        return $this;
    }

    /**
     * Array export
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * Empty the hash before serializing
     */
    public function __sleep()
    {
        $this->properties['hash'] = null;
        return array('properties');
    }

} 