<?php
	session_start();

	$langFrom = $_POST['langFrom'];
	$langTo = $_POST['langTo'];
	$langPhrase = $_POST['langPhrase'];
	$storePhrase = $storeMeanings =  $results = array();

	//start API call
	function dataTranslate($url) {
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 2
		));

		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	//end API call

	//start process API data
	function requestData($x, $y, $z) {
		global $baseURL;
		$baseURL = 'https://glosbe.com/gapi/translate?format=json&pretty=true';
		$baseURL .= '&phrase='.$x.'&from='.$y.'&dest='.$z;
		echo '<p id="result-url"><a href="' .$baseURL. '" target="_blank">URL</a></br>';
		echo '<button class="btn btn-success saveToFav">Click To Save</button>';

		$data = dataTranslate($baseURL);
		$result = json_decode($data);

		if (isset($result->tuc) && is_array($result->tuc)) {
			foreach ($result->tuc as $trans) {
				if (isset($trans->phrase)) {
					foreach ($trans->phrase as $itemPhrase) {	
						$storePhrase[] = $itemPhrase;
					}
				}
				else {
					if (isset($trans->meanings)) {
						foreach ($trans->meanings as $itemMeanings) {
							$storeMeanings[] = $itemMeanings;
						}
					}
					else {
						$storeMeanings = null;
					}
				}

			}
		}
		else {
			$storePhrase = null;
		}

		if (!empty($storePhrase)) {
			if (!empty($storeMeanings)) {
				return array('phrase' => $storePhrase, 'meanings' => $storeMeanings);
			}
			else {
				return array('phrase' => $storePhrase);
			}
		}
		else {
			return null;
		}
		
	}
	//end process API data

	//start request call
	function getLangCode($v) {
		switch ($v) {
			case 'English':
				return 'en';
				break;
			case 'Malay':
				return 'ms';
				break;
			case 'Tamil':
				return 'ta';
				break;
			case 'Japanese':
				return 'ja';
				break;
		}
	}

	$langFrom = getLangCode($langFrom);
	$langTo = getLangCode($langTo);
	$results = requestData($langPhrase, $langFrom, $langTo);
	//end request call
	
	//start display results
	$maxPhrase = sizeof($results['phrase']);
	$countPhrase = $countMeaning = 1;

	if ($results['phrase'] != null) {
		for ($i=0; $i<$maxPhrase; $i++, $countPhrase++) {
			echo '
				<p class="result-text">
					'.$countPhrase. '. ' .$results['phrase'][$i]. '
				</p>
			';
			$i++;
		}

		if (isset($results['meanings'])) {
		$maxMeanings = sizeof($results['meanings']);
			if ($results['meanings'] != null) {
				if ($maxMeanings > 0) {
					echo '<div class="accordion"><h3>Examples</h3><div>';
					foreach ($results['meanings'] as $items) {
						echo '<p class="result-text">' .$countMeaning. '. ' .$items->text. '</p>';
						$countMeaning++;
					}
					echo '</div></div>';
				}
			}
			else {
				echo 'No meanings found!';
			}
		}
	}
	else {
		echo 'No phrases found!';
	}
	//end display results
?>

<script type="text/javascript">
	
	$('.saveToFav').on('click', function() {
		$langPhrase = <?php echo json_encode($langPhrase); ?>;
		$langFrom = <?php echo json_encode($langFrom); ?>;
		$langTo = <?php echo json_encode($langTo); ?>;
		$baseURL = <?php echo json_encode($baseURL); ?>;

		$.ajax({
			type: "POST",
			url: "fav.php",
			data: {
				langFrom: $langFrom,
				langTo: $langTo,
				langPhrase: $langPhrase,
				url: $baseURL
			},
			success: function(result) {
				$('.fav').html(result);
			}
		});
	});

</script>
