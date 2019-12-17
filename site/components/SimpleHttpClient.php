<?php

namespace app\components;

class SimpleHttpClient
{
    /**
     * @var string request URL
     */
    private $url = '';

    /**
     * @var array request data
     */
    private $data;

    /**
     * @var string|null request result
     */
    private $result;

    /**
     * @var string|null last request error
     */
    private $lastError;

    /**
     * @var resource curl resource
     */
    private $resource;

    /**
     * SimpleHttpClient constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;

        $this->resource = curl_init($this->url);
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;

        curl_setopt($this->resource, CURLOPT_POST, true);
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $data);
    }

    public function executeRequest()
    {
        $this->result = curl_exec($this->resource);
        $this->lastError = curl_error($this->resource);
    }

    /**
     * @return array
     */
    public function getResponse(): ?array
    {
        $decoded = json_decode($this->result, true);

        if ($decoded === null) {
            $this->lastError = json_last_error_msg();
            return null;
        }

        return $decoded;
    }

    /**
     * @return string|null
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

}
