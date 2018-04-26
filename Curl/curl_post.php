<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getPost($url, $param)
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
$content = getContent($url);

if(preg_match('~var GL_PRODUCTID = (\d+)~', $content, $matches)&&preg_match_all('~onclick="gotoGallery\(([17]),(\d+)\)"~', $content, $matches1)){
    $imageList = array();
    for($i=0; $i<sizeof($matches1[0]); $i++){
        $param = 'productID='.$matches[1].'&imageType='.$matches1[1][$i].'&colorID='.$matches1[2][$i];
        $content = getPost('https://www.thegioididong.com/aj/ProductV4/GallerySlideFT/', $param);
        if(preg_match_all('~data-img="(.+?)"~', $content, $matches2)){
            if((int)$matches1[1][$i]==1){
                foreach ($matches2[1] as $item){
                    $imageList[] = 'https:'.$item;
                }
            } elseif ((int)$matches1[1][$i]==7){
                foreach ($matches2[1] as $item){
                    $imageList[] = $item;
                }
            }

        }
    }
    var_dump($imageList);
}