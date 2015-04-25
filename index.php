<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('data/config.php');

$action = 'monitor';

$testUrl = APINODE . $action . '?sender=' . APIKEY . '&rbl=';

echo '<html>
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
			</style>
		</head>
		<body>';
echo '<h1>Wallensteinplatz</h1><table>
		<tr>
			<th>Linie</th>
			<th>Richtung</th>
			<th>Abfahrt</th>
		</tr>';

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
	$str = "<table><tr>";
	for ($i=0; $i<sizeof($departures); $i++) {
		$str .= "<td>" . $departures[$i]->departureTime->countdown . "</td>";
	}
	$str .= "</tr></table>";

	return $str;

	//var_dump($departures);

	//$line->departures->departure[0]->departureTime->countdown
}
?>