<?php

function curl($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function curlPost($url, $param)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

$url = 'https://www.thegioididong.com/dtdd/oppo-f7-128gb'; // link 1 sản phẩm của thế giới di động
$content = curl($url);

if (preg_match('~var GL_PRODUCTID = (\d+)~', $content, $productIDMatch)) {
    if (preg_match_all('~onclick="gotoGallery\(([17]),(\d+)\)"~', $content, $imageTypeAndColorIDMatch)) {
        $imageList = array();
        for ($i = 0; $i < sizeof($imageTypeAndColorIDMatch[0]); $i++) {
            $param = 'productID=' . $productIDMatch[1] . '&imageType=' . $imageTypeAndColorIDMatch[1][$i] . '&colorID=' . $imageTypeAndColorIDMatch[2][$i];
            $content = curlPost('https://www.thegioididong.com/aj/ProductV4/GallerySlideFT/', $param);

            if (preg_match_all('~data-img="(.+?)"~', $content, $imageMatch)) {
                if ((int)$imageTypeAndColorIDMatch[1][$i] == 1) {
                    foreach ($imageMatch[1] as $item) {
                        $imageList[] = 'https:' . $item;
                    }
                } elseif ((int)$imageTypeAndColorIDMatch[1][$i] == 7) {
                    foreach ($imageMatch[1] as $item) {
                        $imageList[] = $item;
                    }
                }
            }
        }
        var_dump($imageList);
    }
}