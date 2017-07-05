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
	<div class="row">
  	<div class="well">
    	<center><h1><a href="mastery.php">Champion Mastery</a></h1>
			<p>Personalized Champion Mastery Stats for <?php if($_GET['playername'] != null) {echo "<mark>".$_GET['playername']."</mark>";} else{echo "use in Champion Select.";}?></p></center>
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
	<div class="col-xs-2">
		<button type="submit" class="btn btn-info" style="margin: 25px;">
    	<span class="glyphicon glyphicon-search"></span> Search
  	</button>
	</div>
	</form>
	<div class="col-xs-1"></div>
</div>
<div class="row">
<?php
require_once("../utilities/riotapi.php");

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
        $champions = [ "" => "",];
        $championsKey = [ "" => "",];

	$i = 0;
	$playername = $_GET['playername'];
	$region = $regions[html_entity_decode($_GET['region'])];
	$encodedPlayername = strtolower(urlencode($playername));
	$summonerInfo = $api->get_summonerId($region, $encodedPlayername);
	$champmastery = $api->get_championMastery($region, $summonerInfo['id']);
	$championInfo = $api->get_champList();
	if($champmastery['status'] != null)
	{
		echo "<p class='text-center bg-danger'>Summoner Name: ". $_GET['playername'] ." not found on the ". html_entity_decode($_GET['region']) ." Server</p>";
		echo "</div>
		</body>
		<nav class=\"navbar navbar-fixed-bottom\">
		  <div class=\"container-fluid\">
				<button class=\"btn btn-info\" onclick=\"location.href = \'http://derrickshimada.com\';\">Home</button>
				<button class=\"btn btn-warning\" data-toggle=\"collapse\" data-target=\"#legal\">Legal</button></li>
				<div id=\"legal\" class=\"collapse well\">
				Champion Mastery isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends &copy; Riot Games, Inc.
			</br></br>Copyright &copy; <a href=\"http://derrickshimada.com\">Derrick Shimada</a> 2016-2017 All Rights Reserved.
				</div>
		  </div>
		</nav>
		</html>";
		return;
	}
	echo "<div class='col-xs-1'></div><div class='col-xs-10'><table class='table table-hover table-bordered'> <tr> <th></th> <th>Champion</th> <th>Mastery Level</th> <th>Points</th> <th>Points until lvl up</th> <th>Chest Earned</th> </tr> <tr>";

        foreach ($championInfo['data'] as $champ)
        {
             $tmpId = $champ['id'];
             $champions[$tmpId] = $champ['name'];
             $championsKey[$tmpId] = $champ['key'];
        }
	foreach ($champmastery as $champion)
	{
		$cid = $champion['championId'];
		if (intval($champion['championLevel']) == 7)
		{
			  echo "<tr style='background-color:#b3ffb3'>";
		}
		if (intval($champion['championLevel']) == 6)
		{
			  echo "<tr style='background-color:#e0b3ff'>";
		}
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
		echo "<td><img src='http://ddragon.leagueoflegends.com/cdn/".$championInfo['version']."/img/champion/".$championsKey[$cid].".png' alt='champpic' height='42' width='42' ></td>";
		echo "<td>".$champions[$cid]."</td>";
		echo "<td>".$champion['championLevel']."</td>";
		echo "<td>".$champion['championPoints']."</td>";
		$champProgress = intval($champion['championPoints']) + intval($champion['championPointsUntilNextLevel']);
		$champPercent  = (intval($champion['championPoints']) / intval($champProgress)) * 100;
		echo '<td><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'.$champPercent.'"  aria-valuemin="0" aria-valuemax="100" style="min-width:3em; width:'.$champPercent.'%;">'.$champion['championPointsUntilNextLevel'].'</div></div>';
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
?>
</div>
</body>
<nav class="navbar navbar-fixed-bottom">
  <div class="container-fluid">
    <div class="pull-left">
		<button class="btn btn-info" onclick="location.href = 'http://derrickshimada.com';">Home</button>
    </div>
    <div class="pull-right">
		<button class="btn btn-warning" data-toggle="collapse" data-target="#legal">Legal</button></li>
    </div>
    <div class="clearfix"> </div>
		<div id="legal" class="collapse well">
		Champion Mastery isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends &copy; Riot Games, Inc.
	</br></br>Copyright &copy; <a href="http://derrickshimada.com">Derrick Shimada</a> 2016-2017 All Rights Reserved.
		</div>
  </div>
</nav>


</html>
