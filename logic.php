<?php
	checkNavigationFromMain();
	if (!checkNoFieldEmpty()) {
		returnToMain();
	}

	echo "Starte Berechnung...<br>";
	$darl = $_POST["darl"];
	$zins = $_POST["zins"];
	$laufz = $_POST["laufz"];

	// calculate constant annuität
	$annui = $darl * (1.04**$laufz * 0.4) / (1.04**$laufz - 1);
	$annui = round($annui / 10);

	// calculate and fill all arrays
	list($darlArray, $zinsArray, $tilgungArray, $annuiArray, $restArray, $laufzArray) = calculateData($darl, $zins, $laufz, $annui);

	$data = array(
		
		array('1' => '111', '2' => '222'),
		array('1' => '111111', '2' => '222222'),
	);

	$tableData = array();
	$tableData = transformArraysForTable($darlArray, $zinsArray, $tilgungArray, $annuiArray, $restArray, $laufzArray);


	function html_table($data = array())
	{
    $rows = array();
    foreach ($data as $row) {
        $cells = array();
        foreach ($row as $cell) {
            $cells[] = "<td>{$cell}</td>";
        }
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<table class='hci-table'>" . implode('', $rows) . "</table>";
	}

	echo html_table($tableData);

	function transformArraysForTable($darlArray, $zinsArray, $tilgungArray, $annuiArray, $restArray, $laufzArray) {
		$tableData = array();
		$arrayCount = count($laufzArray);
		echo $arrayCount, "<br/>";

		// run for each row
		for ($y = 0; $y <= $arrayCount; $y++) {
			// create array of row
			$tempArray = array();
			// fill each field of row

			echo "Output<br/>";
			array_push($tempArray, $laufzArray[$y]);
			array_push($tempArray, $zinsArray[$y]);
			array_push($tempArray, $annuiArray[$y]);
			array_push($tempArray, $tilgungArray[$y]);
			array_push($tempArray, $restArray[$y]);
			array_push($tempArray, $darlArray[$y]);

			print_r($tempArray);

			
		}

		return $tableData;
	}


	function checkNoFieldEmpty() {
		if (!empty($_POST["darl"] OR $_POST['zins'] OR $_POST['laufz'])) {
			return true;
		} else {
			return false;
		}
	}

	function checkNavigationFromMain() {
		if (isset($_POST['darl'], $_POST['zins'], $_POST['laufz'])) {
		} else {
			returnToMain();
		}
	}

	function returnToMain() {
		header("Location: http://fbwallinone.th-brandenburg.de/~siebertt/tilgungsplan.html");
		exit();
	}

	function showDialog($message) {
		echo "<script type='text/javascript'>alert('$message');</script>";
	}

	function calculateData($darl, $zins, $laufz, $annui) {
		$darlArray = []; 
		$zinsArray = []; 
		$tilgungArray = [];
		$annuiArray = [];
		$restArray = [];
		$laufzArray = [];

		//make first insert
		$darlArray[] = $darl;
		$zinsArray[] = $darl * $zins / 100;
		$annuiArray[] = $annui;
		$tilgungArray[] = $annui - $zinsArray[0];
		$restArray[] = $darlArray[0] - $tilgungArray[0];
		$laufzArray[] = 1;

		// calculate for each year
		for ($x = 1; $x < $laufz; $x++) {

			// add standards for later tableview
			$laufzArray[$x] = $x+1;
			$annuiArray[$x] = $annui;

			// darl from rest of previous insert
			$darlArray[$x] = $restArray[$x-1];

			// calculate zins
			$zinsArray[$x] = round($darlArray[$x] * $zins / 100);

			// calculate tilgung
			$tilgungArray[$x] = $annuiArray[$x] - $zinsArray[$x];

			// calculate rest
			$restArray[$x] = $darlArray[$x] - $tilgungArray[$x];

		}

		return array($darlArray, $zinsArray, $tilgungArray, $annuiArray, $restArray, $laufzArray);
	}
?>