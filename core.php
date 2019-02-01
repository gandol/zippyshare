<?php
include("./vendor/autoload.php");
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

function fetchUrl($url){
	$guzzleClient = new GuzzleClient(array(
	    'timeout' => 60,
	));
	$client = new Client();
	$client->setClient($guzzleClient);
	$userAgent = 'Mozilla/5.0 (Windows NT 10.0)'
           . ' AppleWebKit/537.36 (KHTML, like Gecko)'
           . ' Chrome/48.0.2564.97'
           . ' Safari/537.36';
	// $url = "https://www108.zippyshare.com/v/NCKW1T9v/file.html";
	$id = explode("/",$url)[4];
	$headers = array('User-Agent' => $userAgent);
	$crawler = $client->request('GET', $url,$headers);
	$javaScript=$crawler->filter('#lrbox > div:nth-child(2) > div:nth-child(2) > div > script')->each(function ($node) {
	    return $node->text();
	});
	$fileName=$crawler->filter('#lrbox > div:nth-child(2) > div:nth-child(1) > font:nth-child(4)')->each(function ($node) {
	    return $node->text();
	});
	$omg = $crawler->filter('#omg')->each(function ($node){
	    return $node->attr("class");
	});

	$satu = strpos($javaScript[0],'"+(');
	$hasilSatu=substr($javaScript[0],$satu);
	$dua = strpos($hasilSatu,"%");
	$hasilDua = substr($hasilSatu,0,$dua);
	$nilai = substr($hasilDua,3);

	$satu = strpos($javaScript[0],'+"/');
	$hasilSatu = substr($javaScript[0],$satu);
	$dua = strpos($hasilSatu,'";');
	$hasilDua = substr($hasilSatu,0,$dua);
	$filenameUrl = substr($hasilDua,3);
	$ketiga =($nilai%1000+a()+b()+c()+($omg[0]*2)+5/5);
	$resultUrl = "https://".explode("/",$url)[2]."/d/".$id."/".$ketiga."/".$filenameUrl;
	$output = new \ stdClass();
	$output->file_name=$fileName[0];
	$output->file_url=$resultUrl;
	$done = json_encode($output);
	return $done;
}
function a(){
    return 1;
}
function b(){
    return a()+1;
}
function c(){
    return b()+1;
}
function download($url){
	$data =json_decode(fetchUrl($url),true);
	$sourceUrl = $data['file_url'];
	$fileName = $data['file_name'];
    $fp = fopen ($fileName, 'w+') or die('Unable to write a file'); 
    $ch = curl_init($sourceUrl);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_FILE, $fp);          
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);     
    curl_setopt($ch, CURLOPT_USERAGENT, 'any');
    curl_setopt($ch, CURLOPT_VERBOSE, true);   
    curl_exec($ch);
    fclose($fp);
}

?>