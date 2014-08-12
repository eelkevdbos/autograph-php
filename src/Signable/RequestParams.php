<?php
/**
 * Created by PhpStorm.
 * User: Eelke
 * Date: 13-8-14
 * Time: 16:38
 */

namespace Autograph\Signable;

class RequestParams
{

    use \Autograph\Signable;

    protected $requestParams = array();

    protected $signatureParamPrefix;

    public function __construct($rawParams = array(), $signatureParamPrefix = 'signature_')
    {
        $this->signatureParamPrefix = $signatureParamPrefix;
        $this->requestParams = $this->filterRequestParams($rawParams);
        $this->setSignatureFromParams($rawParams);
    }

    /**
     * Getter for Signature properties as prefixed associative array
     * @return array
     */
    public function getSignatureParams()
    {
        $signatureParams = $this->getSignature()->toArray();
        $prefixedParams = array();

        foreach ($signatureParams as $k => $v) {
            $prefixedParams[$this->signatureParamPrefix . $k] = $v;
        }

        return $prefixedParams;
    }

    /**
     * Setter for Signature via raw request parameters
     * @param array $rawParams
     * @param bool $filter
     * @return $this
     */
    public function setSignatureFromParams($rawParams, $filter = true)
    {
        $this->setSignature(self::call_user_func_assoc(
            array($this, 'createSignature'),
            $filter ? $this->filterSignatureParams($rawParams) : $rawParams
        ));
        return $this;
    }

    /**
     * Filter non-signature request params,
     * @param $rawParams
     * @param $signaturePrefix
     * @return array
     */
    protected function filterRequestParams($rawParams)
    {
        $requestParams = array();

        foreach ($rawParams as $key => $value) {
            if (strpos($key, $this->signatureParamPrefix) === false) {
                $requestParams[$key] = $value;
            }
        }

        return $requestParams;
    }

    /**
     * Filter signature parameters, matched by the supplied prefix
     * @param $params
     * @param $signaturePrefix
     * @return array
     */
    protected function filterSignatureParams($rawParams)
    {
        $signatureParams = array();
        $prefixLength = strlen($this->signatureParamPrefix);

        foreach ($rawParams as $key => $value) {
            if ($prefixPosition = strpos($key, $this->signatureParamPrefix) !== false) {
                $signatureParams[substr($key, $prefixLength)] = $value;
            }
        }

        return $signatureParams;
    }

    /**
     * Helper to call a member function by matching argument names with keys from the supplied array
     * @param $fn
     * @param $assoc
     * @return array
     */
    public static function call_user_func_assoc($fn, $assoc)
    {
        $reflection = is_array($fn) ? new \ReflectionMethod($fn[0], $fn[1]) : new \ReflectionFunction($fn);

        $export = array();

        foreach ($reflection->getParameters() as $param) {
            $paramName = $param->getName();
            $export[] = array_key_exists($paramName, $assoc) ?
                $assoc[$paramName] : ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
        }

        return call_user_func_array($fn, $export);
    }

} 