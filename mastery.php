<!DOCTYPE html>
<html>
<head>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!-- Bootstrap Mobile First Setting -->
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="container">
  	<div class="well">
    	<h1><a href="mastery.php">Champion Mastery</a></h1>
			<p>Personalized Champion Mastery Stats for <?php if($_GET['playername'] != null) {echo "<mark>".$_GET['playername']."</mark>";} else{echo "use in Champion Select.";}?></p>
  	</div>
	</div>
<div class="row">
	<div class="col-xs-1"></div>
	<form action="mastery.php" method="get">
	<div class="col-xs-6 form-group">
		<label for="summonernameinput">Summoner Name:</label>
		<input type="text" class="form-control" id="summonernameinput" name="playername" placeholder="<?php echo($_GET['playername']);?>"/>
	</div>
	<div class="col-xs-2 form-group">
		<label for="regionddl">Region:</label>
		<select class="form-control" id="regionddl" name="region">
			<option <?php if($_GET['region'] == htmlentities('Brazil')){echo("selected");}?>>Brazil</option>
			<option <?php if($_GET['region'] == htmlentities('EU Nordic & East')){echo("selected");}?>>EU Nordic & East</option>
			<option <?php if($_GET['region'] == htmlentities('EU West')){echo("selected");}?>>EU West</option>
			<option <?php if($_GET['region'] == htmlentities('Japan')){echo("selected");}?>>Japan</option>
			<option <?php if($_GET['region'] == htmlentities('Korea')){echo("selected");}?>>Korea</option>
			<option <?php if($_GET['region'] == htmlentities('Latin America North')){echo("selected");}?>>Latin America North</option>
			<option <?php if($_GET['region'] == htmlentities('Latin America South')){echo("selected");}?>>Latin America South</option>
			<option <?php if($_GET['region'] == htmlentities('North America')){echo("selected");}?>>North America</option>
			<option <?php if($_GET['region'] == htmlentities('Oceania')){echo("selected");}?>>Oceania</option>
			<option <?php if($_GET['region'] == htmlentities('Russia')){echo("selected");}?>>Russia</option>
			<option <?php if($_GET['region'] == htmlentities('Turkey')){echo("selected");}?>>Turkey</option>
			?>
		</select>
	</div>
	<div class="col-xs-1">
		<button type="submit" class="btn btn-info" style="margin: 25px;">
    	<span class="glyphicon glyphicon-search"></span> Search
  	</button>
	</div>
	</form>
	<div class="col-xs-2"></div>
</div>
<div class="row">
<?php
require_once("riotapi.php");

/******** Main ********/
$api = new RiotApi();
if($_GET['playername'] != null && $_GET['region'] != null)
{
	$regions = [
		"Brazil" => "BR1",
		"EU Nordic & East" => "EUN1",
		"EU West" => "EUW1",
		"Japan" => "JP1",
		"Korea" => "KR",
		"Latin America North" => "LA1",
		"Latin America South" => "LA2",
		"North America" => "NA1",
		"Oceania" => "OC1",
		"Russia" => "RU",
		"Turkey" => "TR1",
	];
	//Riot has inconsistent Inputs for their API for Region,
	//this one is for ones specifically without trailing numbers.
	$summonerRegions = [
		"BR1" => "BR",
		"EUN1" => "EUNE",
		"EUW1" => "EUW",
		"JP1" => "JP",
		"KR" => "KR",
		"LA1" => "LAN",
		"LA2" => "LAS",
		"NA1" => "NA",
		"OC1" => "OCE",
		"RU" => "RU",
		"TR1" => "TR",
	];
	$i = 0;
	$playername = $_GET['playername'];
	$region = $regions[html_entity_decode($_GET['region'])];
	$summonerRegion = $summonerRegions[$region];
	$lcplayername = strtolower($playername);
	$summonerInfo = $api->get_summonerId($summonerRegion, $playername);
	$champmastery = $api->get_championMastery($region, $summonerInfo[$lcplayername]['id']);
	$championInfo = $api->get_champList();
	echo "<div class='col-lg-1'></div><div class='col-lg-10'><table class='table table-hover table-bordered'> <tr> <th></th> <th>Champion</th> <th>Mastery Level</th> <th>Points</th> <th>Points until lvl up</th> <th>Highest Grade</th> <th>Chest Earned</th> </tr> <tr>";
	foreach ($champmastery as $champion)
	{
		$cid = $champion['championId'];
		if (intval($champion['championLevel']) == 5)
		{
				echo "<tr class='success'>";
		}
		if (intval($champion['championLevel']) == 4)
		{
				echo "<tr class='info'>";
		}
		if (intval($champion['championLevel']) == 3)
		{
				echo "<tr class='warning'>";
		}
		if (intval($champion['championLevel']) == 2)
		{
				echo "<tr class='danger'>";
		}
		if (intval($champion['championLevel']) == 1)
		{
				echo "<tr>";
		}
		echo "<td><img src='http://ddragon.leagueoflegends.com/cdn/6.7.1/img/champion/".$championInfo['data'][$cid]['key'].".png' alt='champpic' height='42' width='42' ></td>";
		echo "<td>".$championInfo['data'][$cid]['name']."</td>";
		echo "<td>".$champion['championLevel']."</td>";
		echo "<td>".$champion['championPoints']."</td>";
		$champProgress = intval($champion['championPoints']) + intval($champion['championPointsUntilNextLevel']);
		$champPercent  = (intval($champion['championPoints']) / intval($champProgress)) * 100;
		//echo "<td>".$champion['championPointsUntilNextLevel']."</td>";
		echo '<td><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'.$champPercent.'"  aria-valuemin="0" aria-valuemax="100" style="min-width:3em; width:'.$champPercent.'%;">'.$champion['championPointsUntilNextLevel'].'</div></div>';
		echo "<td>".$champion['highestGrade']."</td>";
		if(intval($champion['chestGranted']) == 1)
		{
			echo "<td><span class='glyphicon glyphicon-briefcase'></span></td>";
		}
		else{
			echo "<td>".$champion['chestGranted']."</td>";
		}
		echo "</tr>";
		$i++;
	}
	echo "</table></div>";
}
else {

}
?>
</div>
</body>
<nav class="navbar navbar-fixed-bottom">
  <div class="container-fluid">
    <button class="btn btn-warning" data-toggle="collapse" data-target="#legal">Legal</button></li>
		<div id="legal" class="collapse well">
		Champion Mastery isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends © Riot Games, Inc.
	</br></br>Copyright 2016 Derrick Shimada
		</div>
  </div>
</nav>


</html>
