<?php

namespace App\Services\RbcNews;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Loader
{
    /**
     * @param string $url
     *
     * @return string|null
     *
     * @throws HttpException
     */
    public function getHtmlByUrl(string $url): ?string
    {
        $file = curl_init($url);

        curl_setopt($file, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($file, CURLOPT_HEADER, false);
        curl_setopt($file, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($file, CURLOPT_MAXREDIRS, 5);
        curl_setopt($file, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36');

        try {
            $data = curl_exec($file);
            if (Response::HTTP_OK === $httpCode = curl_getinfo($file, CURLINFO_RESPONSE_CODE)) {
                return $data;
            }
            curl_close($file);

            if (0 === $httpCode) {
                throw new RuntimeException('Cannot connect to ' . $url);
            }

            throw new HttpException($httpCode);
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
