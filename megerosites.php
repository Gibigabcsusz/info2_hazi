<!DOCTYPE html>
<?php session_start();

include 'db.php';
$link=opendb();

if(isset($_SESSION['felhasznalonev']))
{
	if(isset($_GET['try']) and $_GET['try']==1)
	{
		$vasarlo_id=mysqli_fetch_array(mysqli_query($link, "SELECT id FROM vasarlo WHERE felhasznalonev='" . $_SESSION['felhasznalonev'] . "';"))['id'];
		$query = sprintf("DELETE FROM kosar WHERE vasarlo_id=%s;", $vasarlo_id);
		$query2 = sprintf("DELETE FROM vasarlo WHERE id=%s;", $vasarlo_id);

		$eredmeny=mysqli_query($link, $query);
		$eredmeny2=mysqli_query($link, $query2);

		if($eredmeny2 and $eredmeny) // sikeres törlés esetén OK gomb, ami visszavisz a kezdőlapra kijelentkeztetve
		{
			header("Location: ok.php?siker=1");
		}
		else
		{
			header("Location: ok.php?siker=0");
		}
	}
}
else
{
	header("Location: index.php");
}


?>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Bejelentkezés</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Biztosan törölni akarod a felhasználói fiókodat?</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// törlés megerősítése
echo '<li id="torles" class="menu_gomb"><a href="http://localhost/nagyhazi/megerosites.php?try=1">Törlés!</a></li>';

// újratöltés (a jobb oldalon a gombok között)
echo '<li class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/index.php">Mégse</a></li>';

mysqli_close($link);
?>

</ul>

</div>
</body>
</html>
