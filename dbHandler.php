<?php
include_once("./Class/MyMongo.php");

$val = $_GET['cont'];

$val=urlencode($val);

$lastfmUrl="http://ws.audioscrobbler.com/2.0/?method=artist.getcorrection&artist=".$val."&api_key=KLUCZ&limit=1&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$lastfmUrl");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);

$result=json_decode($result);


if(!isset($result->error))
{
	if(!is_string($result->corrections))
	{
		$val=urlencode($result->corrections->correction->artist->name);
	}
}




$mongo = new MyMongo();
$mongoh = $mongo->connect();
$mongodb = $mongoh->test;

$prefix_size=5;
$found=0;

$coll = $mongo->listColl($mongodb);


for($i=0;$i<sizeof($coll);$i++)
{
	if(substr($coll[$i],$prefix_size)==$val)
	{
		$found=$i;
		break;
	}
}

if($found==0)
{
	$lastfmUrl="http://ws.audioscrobbler.com/2.0/?method=artist.getpastevents&artist=".$val."&api_key=KLUCZ&limit=1&format=json";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$lastfmUrl");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	//echo $result;
	

	
	if(!isset(json_decode($result)->error))
	{
	
	//echo print_r(json_decode($result));
	$eventCount = json_decode($result)->events->{'@attr'}->total;
	
	$lastfmUrl="http://ws.audioscrobbler.com/2.0/?method=artist.getpastevents&artist=".$val."&api_key=KLUCZ&limit=".$eventCount."&format=json";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$lastfmUrl");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	
	
	$result=json_decode($result);
	
	
	$result=pullData($result);
	
	//echo json_encode($result);
	
	$mongo->createColl($mongodb,$val);
	$newColl = $mongo->selectColl($mongodb,$val);
	for($k=0;$k<sizeof($result);$k++)
	{
		$mongo->setColl($newColl,$result[$k]);
	}
	

	
	}
	else if(json_decode($result)->error=="6")
	{
		$rrr = <<<QUO
evalthis^$("#band").css("border-color","red");
QUO;
	echo $rrr;
	}
}

$coll = $mongo->selectColl($mongodb,$val);
$cursor = $coll->find();



$ret = "print^";
$years=array();
foreach ($cursor as $bla)
{
	//$temp=sizeof($years[$bla["year"]]);
//	if(isset($years[$bla["year"]]))
//	{
		$years[$bla["year"]]=array();
//	}
//	array_push($years[$bla["year"]],$bla);
	//$ret = $ret.print_r($bla["year"]);
}

foreach ($cursor as $bla)
{
	array_push($years[$bla["year"]],$bla);
}
/*
foreach($years as $year)
{
	$ret=$ret.$year["year"]."<BR>";
}*/

echo $ret.json_encode($years);


function pullData($result)
{

	for ($i=0;$i<sizeof($result->events->event);$i++)
	{
	$dbObj[$i]->event=$result->events->event[$i]->title;
	$dbObj[$i]->place=$result->events->event[$i]->venue->location->city;
	$dbObj[$i]->country=$result->events->event[$i]->venue->location->country;
	$dbObj[$i]->long=$result->events->event[$i]->venue->location->{'geo:point'}->{'geo:long'};
	$dbObj[$i]->lat=$result->events->event[$i]->venue->location->{'geo:point'}->{'geo:lat'};
	$dbObj[$i]->date=$result->events->event[$i]->startDate;
	$dbObj[$i]->year=explode(" ",$dbObj[$i]->date);
	$dbObj[$i]->year=$dbObj[$i]->year[3];
	}
	return $dbObj;
}

//echo substr($coll[0],$prefix_size);

/*foreach ($coll as $col)
{
$val = $col."<br/>";

}*/

//$val = json_encode($coll);
//echo $val;

?>
