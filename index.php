<?php
$minPrice = 0;
$maxPrice = PHP_INT_MAX;
$minBed = 0;
$maxBed = PHP_INT_MAX;
$minBath = 0;
$maxBath = PHP_INT_MAX;
if(isset($_GET["min_price"]))
	$minPrice = $_GET["min_price"];
if(isset($_GET["max_price"]))
	$maxPrice = $_GET["max_price"];
if(isset($_GET['min_bed']))
	$minBed = $_GET['min_bed'];
if(isset($_GET['max_bed']))
	$maxBed = $_GET['max_bed'];
if(isset($_GET['min_bath']))
	$minBath = $_GET['min_bath'];
if(isset($_GET['max_bath']))
	$maxBath = $_GET['max_bath'];

$header = NULL;
$data = array();

if (($handle = fopen("listings.csv", 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle)) !== FALSE)
	{
		if(!$header)
			$header = $row;
		else
		{
			$price = $row[3];
			$bed = $row[4];
			$bath = $row[5];
			if($price >= $minPrice && $price <= $maxPrice && $bed >= $minBed && $bed <= $maxBed && $bath >= $minBath && $bath <= $maxBath)
			{
				$property = array("id"=>$row[0], "price"=>$price, "street"=>$row[1], "bedrooms"=>$bed, "bathrooms"=>$bath, "sq_ft"=>$row[6]);
				$coordinates = array($row[7], $row[8]);
				$geometry = array("type"=>"Point", "coordinates"=>$coordinates);
				$entry = array("type"=>"Feature", "properties"=>$property, "geometry"=>$geometry);
				$data[] = $entry;
			}
		}
	}
	fclose($handle);
}
$geoJson = array("type"=>"FeatureCollection", "features"=>$data);
echo json_encode($geoJson);
?>
