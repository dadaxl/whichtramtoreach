<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('data/config.php');

$action = 'monitor';

$testUrl = APINODE . $action . '?sender=' . APIKEY . '&rbl=';

echo '<!DOCTYPE html>
	  <html itemscope itemtype="http://schema.org/QAPage">
		<head>
			<style>
				body {
					background: #f0f0f0;
					font-family: arial, sans-serif;
				}

				table {
					width: 100%;
				}

				th {
					text-align: left;
					display: none;
					font-size: 36px;
				}

				td {
					background: #000000;
					color: yellow;
					padding: 10px;
					font-size: 24px;
				}

				.notreachable {
					color: red;
					opacity: 0.5;
				}

				.reachable {
					color: green;
					opacity: 1;
				}

				.disabled {
					opacity: 0.2;
				}

				#recommended {
					float: left;
					margin-top: 40px;
					width: 100%;
					text-align: center
				}

				#recommended div {
					margin: 0 auto;
					border-radius: 130px;
					-moz-border-radius: 130px;
					background: #000;
					width: 400px;
					color: #fff;
					font-size: 336px;
					padding: 60px 0px;
					text-align: center

				}
			</style>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		</head>
		<body>';
echo '<table class="trams">';

foreach ($relevantRbls as $rbl) {
	$testData = testRequest($testUrl . $rbl);

	$dataObj = json_decode($testData);

	//var_dump();

	if (!!$dataObj->data->monitors) {
		$lineObj = $dataObj->data->monitors[0]->lines;
		renderLines($lineObj);		
	}

}

echo '</tr></table>
	  <div id="recommended">
	  	<div></div>
	  </div>
	  <script src="js/main.js"></script>
	  <script>
	  	window.setTimeout(function() {location.reload();}, 6000);
	  </script>';

echo '</body></html>';

function testRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function renderLines($lines) {

	foreach ($lines as $line) {
		echo '<tr><td>' . $line->name . '</td>' .
			 '<td>' . $line->towards . '</td>' .
			 '<td>' . renderDepartures($line->departures->departure) . '</td></tr>';
	}



	//var_dump($lines);
}

function renderDepartures($departures) {
	$str = '<table class="departures"><tr>';
	for ($i=0; $i<sizeof($departures); $i++) {
		$str .= "<td>" . checkReachAbility($departures[$i]->departureTime->countdown) . "</td>";
	}
	$str .= "</tr></table>";

	return $str;

	//var_dump($departures);

	//$line->departures->departure[0]->departureTime->countdown
}

function checkReachAbility($countdown) {
	if ($countdown <= $GLOBALS["timeBufferInMinutes"]) {
		return '<span class="notreachable">' . $countdown . '</span>';
	} else {
		return '<span class="reachable">' . $countdown . '</span>';		
	}
}
?>