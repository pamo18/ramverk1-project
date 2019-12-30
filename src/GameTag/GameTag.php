<?php

namespace Pamo\GameTag;

use Pamo\MultiCurl\MultiCurl;

/**
 * GameTag
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class GameTag
{
    /**
     * @var object $multiCurl tool.
     * @var string $baseAddress for the API.
     */
    private $multiCurl;
    private $baseAddress;



    /**
     * GameTag a tag
     *
     * @param string $baseAddress for the API.
     * @param string $apiKey for authentication.
     *
     */
    public function init()
    {
        $this->multiCurl = new MultiCurl();
        $this->baseAddress = "https://api.rawg.io";
    }



    /**
     * Return the API Data
     *
     * @param string $ipAddress is the IP Address to validate.
     *
     * @return array
     */
    public function getAllData(string $params) : array
    {
        $url = ["$this->baseAddress" . "$params"];
        $header = [
            "Content-Type: application/json",
            "charset=utf-8",
            "User-Agent=GameOverflow"
        ];

        $data = $this->multiCurl->get($url, $header);

        if ($data && !empty($data[0]["results"])) {
            $buildData = $this->build($data[0]);
        }

        $result = isset($buildData) ? $buildData : [];

        return $result;
    }



    /**
     * Return the organized API Data
     *
     * @param array $data from the IP.
     *
     * @return array
     */
    private function build(array $data) : array
    {
        $buildData = [
            "next" => isset($data["next"]) ? $data["next"] : null,
            "previous" => isset($data["previous"]) ? $data["previous"] : null,
            "results" => []
        ];

        foreach ($data["results"] as $row) {
            $result = [
                "name" => empty($row["name"]) ? null: $row["name"],
                "parents" => empty($row["parent_platforms"]) ? null : $row["parent_platforms"],
                "platforms" => empty($row["platforms"]) ? null : $row["platforms"],
                "released" => empty($row["released"]) ? null : $row["released"],
                "image" => empty($row["background_image"]) ? null : $row["background_image"],
                "video" => empty($row["clip"]["clip"]) ? null : $row["clip"]["clip"],
                "genres" => empty($row["genres"]) ? null : $row["genres"]
            ];
            array_push($buildData["results"], $result);
        }

        return $buildData;
    }
}
