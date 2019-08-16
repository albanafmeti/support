<?php

namespace Noisim\Support\Services\Http\ResponseService;


class SimpleResponseBody
{
    /**
     * Contains all the response body fields as an array.
     * @var array array
     */
    private $content = [];

    /**
     * Set the content of response body.
     *
     * @param array $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the content of response body.
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Get the array representation of this object.
     * Since `$content` is an array we return it as it is.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->content;
    }

    /**
     * When we access an non-existent property from this class object, we check if there is a key in $content array and we return it.
     * Otherwise we return null.
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (isset($this->content[$name])) {
            return $this->content[$name];
        }
    }


    /**
     * Return the `success` value of the response body.
     * @return bool|null
     */
    public function isSuccess(): bool
    {
        if (isset($this->content['success'])) {
            return $this->content['success'] ? true : false;
        }

        return null;
    }

    /**
     * Get the specified key of `data` field from the request body.
     *
     * @param string|null $key
     * @return mixed|null
     */
    public function data(string $key = null)
    {
        if ($key) {
            $matrixKeys = explode('.', $key);

            $value = $this->content['data'] ?? null;

            foreach ($matrixKeys as $key) {
                $value = $value[$key] ?? null;
            }

            return $value ?? null;
        }

        return $this->content['data'] ?? null;
    }
}