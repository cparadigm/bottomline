<?php



/**
 * Thrown when an API call returns an error
 */
class BssApiException extends Exception
{
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const GONE = 410;
    const INVALID_RECORD = 422;
    const SERVER_ERROR = 500;

    /**
     * The result containing errors from our server
     */
    protected $result;

    /**
     * Make a new BSS Exception with the given result.
     *
     * @param array $result Result from the API server
     */
    public function __construct($result)
    {
        $this->result = $result;

        $msg = $result['message'];
        $code = isset($result['code']) ? $result['code'] : 0;

        parent::__construct($msg, $code);
    }

    /**
     * Return the associated result object returned by the API server.
     *
     * @return array The result from the API server
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * To make debugging easier.
     *
     * @return string The string representation of the error
     */
    public function __toString()
    {
        $str = 'Exception: ';
        if ($this->code != 0) {
            $str .= $this->code . ': ';
        }

        return $str . $this->message;
    }
}
