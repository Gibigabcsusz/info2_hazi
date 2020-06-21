<!DOCTYPE html>
<?php session_start();?>


<html>



<head>
    <link rel="stylesheet" href="styles.css">
	<title>Megerősítés</title>
</head>

<body>
<?php /*
<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Biztosan törölni akarod ezt a felhasználót: "<?=$_SESSION['felhasznalonev']?>"?</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">
*/?>
<?php
//bejelentkezés linkje
if(isset($_SESSION['felhasznalonev']))
{
	if(isset($_GET['felhasznalo_torlese']) and $_GET['felhasznalo_torlese']==1)
	{
		// kísérlet a felhasználó törlésére ///////////////

		$vasarlo_id=mysqli_fetch_array(mysqli_query($link, "SELECT id FROM vasarlo WHERE felhasznalonev='" . $_SESSION['felhasznalonev'] . "';"))['id'];

		$query = sprintf("DELETE FROM kosar WHERE vasarlo_id=%s;", $vasarlo_id);
		$query2 = sprintf("DELETE FROM vasarlo WHERE id=%s;", $vasarlo_id);

		$eredmeny=mysqli_query($link, $query);
		$eredmeny2=mysqli_query($link, $query2);

		if($eredmeny2 and $eredmeny) // sikeres törlés esetén OK gomb, ami visszavisz a kezdőlapra kijelentkeztetve
		{
			echo '<h1>"' . $_SESSION['felhasznalonev'] . '" felhasználó törlése megtörtént</h1>';
			
			//TODO: sikeres volt, ok gomb

			session_unset();
			session_destroy();

		}
		else
		{
			//TODO: sikertelen volt, ok gomb
			echo '<h1>"' . $_SESSION['felhasznalonev'] . '" felhasználó törlése <i>sikertelen</i> volt</h1>';
		}
	}
	else 
	{
		//TODO: megkérdezni, hogy biztosan törölni akar-e
	}
	// mégse
	echo '<li class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/index.php">Mégse</a></li>';

	// törlés
	echo '<li id="torles" class="menu_gomb"><a href="http://localhost/nagyhazi/ertesites.php?felhasznalo_torlese=1">Törlés!</a></li>';
}
else
{
	header("Location: index.php");
}


?>

</ul>
<!--</div>-->
</div>













<?php /*
include 'db.php';
$link = opendb();

if(isset($_SESSION['felhasznalonev']))
{
	$vasarlo_id=mysqli_fetch_array(mysqli_query($link, "SELECT id FROM vasarlo WHERE felhasznalonev='" . $_SESSION['felhasznalonev'] . "';"))['id'];

	$query = sprintf("DELETE FROM kosar WHERE vasarlo_id=%s;", $vasarlo_id);
	$query2 = sprintf("DELETE FROM vasarlo WHERE id=%s;", $vasarlo_id);

	$eredmeny=mysqli_query($link, $query);
	$eredmeny2=mysqli_query($link, $query2);

	if($eredmeny2 and $eredmeny)
	{
		echo '<h1>"' . $_SESSION['felhasznalonev'] . '" felhasználó törlése megtörtént</h1>';
		session_unset();
		session_destroy();
	}
	else
	{
		echo '<h1>"' . $_SESSION['felhasznalonev'] . '" felhasználó törlése <i>sikertelen</i> volt</h1>';
	}
}

mysqli_close($link);*/
?>
<p style="text-align:center;">
<a href="http://localhost/nagyhazi/index.php"><button><b>OK</b></button></a>
</p>
</body>

</html>
