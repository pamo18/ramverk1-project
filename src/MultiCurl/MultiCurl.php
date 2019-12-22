<?php

namespace Pamo\MultiCurl;

/**
 * Multi curl
 */
class MultiCurl
{
    /**
     * Multi curl
     *
     * @param array $url list to curl.
     *
     * @return array
     */
    public function get(array $urls, $header) : array
    {
        $id = 1;
        $data = [];
        $headers = $header;

        //create the multiple cURL handle
        $curlHandler = curl_multi_init();

        foreach ($urls as $url) {
            // create cURL resources
            ${"ch$id"} = curl_init($url);

            // set URL and other appropriate options
            curl_setopt(${"ch$id"}, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(${"ch$id"}, CURLOPT_HTTPHEADER, $headers);

            //add the handle
            curl_multi_add_handle($curlHandler, ${"ch$id"});

            $id++;
        }

        //execute the multi handle
        $running = null;
        do {
            curl_multi_exec($curlHandler, $running);
        } while ($running);

        $id = 1;

        foreach ($urls as $url) {
            //close the handle
            curl_multi_remove_handle($curlHandler, ${"ch$id"});
            $contents = json_decode(curl_multi_getcontent(${"ch$id"}), JSON_UNESCAPED_UNICODE |true);
            $data[] = $contents;
            $id++;
        }

        curl_multi_close($curlHandler);

        return $data;
    }
}
