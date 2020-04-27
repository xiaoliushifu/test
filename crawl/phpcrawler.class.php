<?php

class CuteCrawler {

    public function getContentByFopen($url) {
        $file = fopen($url,'r');
        $content="";
        if ($file) {
            while(($buffer = fgets($file,10240)) !== false) {
                $content .=$buffer;
            }
            fclose($file);
        }
        return $content;
    }

    public function getConByCurl($url) {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $con = curl_exec();
        curl_close($ch);
        return $con;
    }

    public function extractLink($page) {
        $matches = [];
        preg_match_all('#https://www.kugou.com/song/(.*?)\.html#',$page,$matches);
        print_r($matches);
    }
}

$craw = new CuteCrawler();
$url = "https://www.kugou.com/yy/rank/home/1-8888.html?from=rank";
$con = $craw->getContentByFopen($url);

$craw->extractLink($con);
//echo $con;