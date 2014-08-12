<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 12-8-14
 * Time: 21:47
 */

namespace Autograph\Support\Guzzle;

use Autograph\Signable\RequestParams;
use Autograph\Token;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\EmitterInterface;

class SignRequestSubscriber implements SubscriberInterface {

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function getEvents()
    {
        return array('before' => array('signRequest', 'last'));
    }

    public function signRequest(BeforeEvent $event, $name, EmmiterInterface $emitter)
    {
        $request = $event->getRequest();

        $signable = new RequestParams($query = $request->getQuery());
        $signable->sign($this->token);

        foreach ($signable->getSignatureParams() as $k => $v) {
            $query->set($k, $v);
        }
    }

} 