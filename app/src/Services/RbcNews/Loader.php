<?php

namespace App\Services\RbcNews;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Loader
{
    /**
     * @param string $url
     *
     * @return string
     *
     * @throws HttpException
     */
    public function getHtmlByUrl($url)
    {
        $file = curl_init($url);

        curl_setopt($file, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($file, CURLOPT_HEADER, false);
        curl_setopt($file, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($file, CURLOPT_MAXREDIRS, 5);
        curl_setopt($file, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36');

        try {
            $data = curl_exec($file);
            if ($data) {
                return $data;
            }
            curl_close($file);
        } catch (HttpException $e) {
            throw $e;
        }
    }
}
