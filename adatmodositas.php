<!DOCTYPE html>
<?php session_start();
if(!isset($_SESSION['felhasznalonev']))
{
	header("Location: index.php");
}

include 'db.php';
$link=opendb();

?>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Adatok módosítása</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Adatok módosítása</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/adatmodositas.php">Újratöltés</a></li>';

	$visszajelzes="";

if(isset($_GET['try']) and $_GET['try']==1){

	

	$jo_jj=(int)((isset($_POST['jelenlegi_jelszo'])) and ($_POST['jelenlegi_jelszo']!=""));
	$jo_uj=(int)(isset($_POST['uj_jelszo']) and $_POST['uj_jelszo']!="");
	$jo_uju=(int)(isset($_POST['uj_jelszo_ujra']) and $_POST['uj_jelszo_ujra']!="");
	$jo_bn=(int)(isset($_POST['becsuletes_nev']) and $_POST['becsuletes_nev']!="");
	$jo_szd=(int)(isset($_POST['szuletesi_datum']) and $_POST['szuletesi_datum']!="");





// a jelenlegi jelszó ellenőrzése

	if($jo_jj)
	{
		$query = sprintf('select felhasznalonev from vasarlo where jelszo="%s" and felhasznalonev="%s";', mysqli_real_escape_string($link, $_POST['jelenlegi_jelszo']), mysqli_real_escape_string($link, $_SESSION['felhasznalonev']));
		$eredmeny = mysqli_fetch_array(mysqli_query($link, $query));
		if(!(isset($eredmeny['felhasznalonev']) and $eredmeny['felhasznalonev']==$_SESSION['felhasznalonev']))
		{
			$visszajelzes = $visszajelzes . "<p class='hibauzenet'><b>Helytelen jelenlegi jelszó!</b></p>";
		}
	}
	else
	{
		$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>Nem adtad meg a jelenlegi jelszavadat!</b></p>";
	}

// jelszó ellenőrzése

	if(!($jo_uj))
	{
		$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>Nem adtál meg jelszót!</b></p>";
	}

// jelszó megerősítés ellenőrzése

	if($jo_uju)
	{
		if($_POST['uj_jelszo']!=$_POST['uj_jelszo_ujra'])
		{
			$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>A megadott új jelszavak nem egyeznek!</b></p>";
		}
	}
	else
	{
		$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>Nem erősítetted meg az új jelszavad!</b></p>";
	}

// becsületes név ellenőrzése

	if(!($jo_bn))
	{
		$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>Nem adtad meg a becsületes nevedet!</b></p>";
	}

// dátum ellenőrzése

	if(!($jo_szd))
	{
		$visszajelzes = $visszajelzes . "<br><p class='hibauzenet'><b>Nem adtál meg születési dátumot!</b></p>";
	}

	if($visszajelzes=="")
	{
		$uj=mysqli_real_escape_string($link, $_POST['uj_jelszo']);
		$bn=mysqli_real_escape_string($link, $_POST['becsuletes_nev']);
		$szd=mysqli_real_escape_string($link, $_POST['szuletesi_datum']);
		$fn=mysqli_real_escape_string($link, $_SESSION['felhasznalonev']);

		$query = "UPDATE vasarlo SET jelszo = '" . $uj . "', becsuletes_nev = '" . $bn . "', szuletesi_datum = '" . $szd . "' WHERE felhasznalonev='" . $fn . "';";
		mysqli_query($link, $query);
		mysqli_close($link);

		header("Location: index.php");
	}
}
?>
</ul>

</div>
<?php // default értékek
$vasarlo_adatok=mysqli_fetch_array(mysqli_query($link, "SELECT becsuletes_nev, szuletesi_datum FROM vasarlo WHERE felhasznalonev='" . $_SESSION['felhasznalonev'] . "';"));


$default_becsuletes_nev=$vasarlo_adatok['becsuletes_nev'];
$default_szuletesi_datum=$vasarlo_adatok['szuletesi_datum'];





?>
<div id="test">

<div id="form_blokk">
	<form action="adatmodositas.php?try=1" method="post">
			<p>
				Felhasználónév: <?=$_SESSION['felhasznalonev']?>
 			</p>
 			<p>
				Jelenlegi elszó:<br><input type="password" name="jelenlegi_jelszo" />
 			</p>
 			<p>
				Új jelszó:<br><input type="password" name="uj_jelszo" />
 			</p>
 			<p>
				Új jelszó újra:<br><input type="password" name="uj_jelszo_ujra" />
 			</p>
			<p>
				Becsületes név:<br><input type="text" name="becsuletes_nev" value='<?=$default_becsuletes_nev?>'/>
 			</p>
 			<p>
				Születési dátum:<br><input type="date" name="szuletesi_datum" value='<?=$default_szuletesi_datum?>'/>
 			</p>
 			<p>
 				<input type="submit" value="Adatok frissítése" name="keres" />
 			</p>
	</form>
</div>

<div id="hibauzenet_blokk">
	<?=$visszajelzes?>
</div>

</div>

</body>
</html>
