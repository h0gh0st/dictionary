<?php

	function storeFav($x, $y, $z) {
		$inp = file_get_contents('store-fav.json');
		$tempArray = json_decode($inp, true);
		$json = $storePhrase = $storeURL = array();

		if (!empty($inp)) {
			if(array_key_exists($x, $tempArray['lang'])) {
				$json = array('phrase' => $y, 'url' => $z);
				array_push($tempArray['lang'][$x], $json);
				file_put_contents('store-fav.json', json_encode($tempArray));
			}
			else {
				$json = array($x => array('0' => array('phrase' => $y, 'url' => $z)));
				array_push($tempArray['lang'], $json);
				file_put_contents('store-fav.json', json_encode($tempArray));
			}
		}
		else {
			$json = array('lang'=> array('0'=> array($x=> array('0'=> array('phrase'=>$y, 'url'=>$z)))));
			file_put_contents('store-fav.json', json_encode($json));

		}
	}

	$inp = file_get_contents('store-fav.json');
	$tempArray = json_decode($inp, true);
	$json = $storePhrase = $storeURL = array();

	if (isset($_POST['langFav'])) {
		$langFav = $_POST['langFav'];
		switch ($langFav) {
			case 'en':
				$step = 0;
				break;
			case 'ms':
				$step = 1;
				break;
			case 'ta':
				$step = 2;
				break;
			case 'ja':
				$step = 3;
				break;
		}
		for ($i=0; $i<sizeof($tempArray['lang'][$step][$langFav]); $i++) {
			$str = $tempArray['lang'][$step][$langFav][$i]['phrase'];
			$str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
			    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
			}, $str);

			echo $i+1 .'. <a target="_blank" href="' .$tempArray['lang'][$step][$langFav][$i]['url']. '">
			' .$str. '</a></br>';
		}
	}

	if (isset($_POST['langFrom'], $_POST['langTo'], $_POST['langPhrase'], $_POST['url'])) {
		$langTo = $_POST['langTo'];
		$langFrom = $_POST['langFrom'];
		$langPhrase = $_POST['langPhrase'];
		$url = $_POST['url'];

		if ($langFrom == 'en' && $langTo == 'en') {
			$langFav = 'en';
		}
		elseif ($langFrom == 'ms' || $langTo == 'ms') {
			$langFav = 'ms';
		}
		elseif ($langFrom == 'ta' || $langTo == 'ta') {
			$langFav = 'ta';
		}
		elseif ($langFrom == 'ja' || $langTo == 'ja') {
			$langFav = 'ja';
		}

		storeFav($langFav, $langPhrase, $url);

	}



?>
