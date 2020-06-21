<!DOCTYPE html>
<?php session_start();

include 'db.php';
$link=opendb();

//<!--------------------------- innentől a kijelentkeztetés található ---------------------------------->

if(isset($_GET['kijelentkezes']) and $_GET['kijelentkezes']==1)
{
	session_unset();
	session_destroy();
}





//<!--------------------------- innentől a kosár kezelése található ---------------------------------->

if(isset($_SESSION['felhasznalonev'])){

$eredmeny=mysqli_query($link, "SELECT id, raktaron, termeknev, egysegar FROM termek");

// 2D array a raktáron lévő termékek tárolására $termek_tomb: [ id => array(id, termeknev, raktaron, egysegar) ]

$termek_tomb=array();
while($row=mysqli_fetch_array($eredmeny))
{
	$termek_tomb[$row['id']]=array('termeknev'=>$row['termeknev'], 'raktaron'=>$row['raktaron'], 'id'=>$row['id'], 'egysegar'=>$row['egysegar']);
}

/////////////////////////////////////////////////////////////

// a bejelentkezett vásárló adatbázisbeli ID-je
$vasarlo_id=mysqli_fetch_array(mysqli_query($link, "SELECT id FROM vasarlo WHERE felhasznalonev='" . $_SESSION['felhasznalonev'] . "';"))['id'];


// a bejelentkezett vásárló kosarának tartalma (termék ID-je, termék mennyisége)
$eredmeny=mysqli_query($link, "SELECT termek_id, termek_mennyiseg FROM kosar WHERE vasarlo_id=" . mysqli_real_escape_string($link, $vasarlo_id) . ";");

$kosar=array();

if($eredmeny!=false){
	while($row=mysqli_fetch_array($eredmeny)) // $kosar: [ termek_id => termek_mennyiseg ]
	{
		$kosar[$row['termek_id']]=$row['termek_mennyiseg'];
	}
}


//////////////////////////////////////////////////////////////
foreach ($termek_tomb as $aktualis_termek)
{
	// ha éppen annál a terméknél járunk, amelyiket a kosarába rakja a felhasználó
	if(isset($_POST[$aktualis_termek['id']]) and $_POST[$aktualis_termek['id']]=="Kosárba")
	{
		// az elérhető termékek árának csökkentése egyel és a kosárba beszúrás
		if($aktualis_termek['raktaron']>0) //ha van még a raktárban
		{
			// az elérhető termékek csökkentése egyel
			mysqli_query($link, "UPDATE termek SET raktaron=" . mysqli_real_escape_string($link, $aktualis_termek['raktaron']-1) . " WHERE id=" . $aktualis_termek['id'] . ";");

			// a kosárban lévő termék darabszámának növelése egyel
			if(isset($kosar[$aktualis_termek['id']])) //ha már van ilyen termék a kosarában
			{
				mysqli_query($link, "UPDATE kosar SET termek_mennyiseg=" . mysqli_real_escape_string($link, $kosar[$aktualis_termek['id']]+1) . " WHERE termek_id=" . $aktualis_termek['id'] . ";");
			}
			else //ha még nincs ilyen termék a kosarában
			{
				mysqli_query($link, "INSERT INTO kosar(vasarlo_id, termek_id, termek_mennyiseg) VALUES(" . $vasarlo_id . ", " . $aktualis_termek['id'] . ", 1);");
			}
		}
	}

	// ha éppen annál a terméknél járunk, amelyikből visszarak egyet a polcra a felhasználó
	if(isset($_POST[$aktualis_termek['id']]) and $_POST[$aktualis_termek['id']]=="Egyet visszateszek" and isset($kosar[$aktualis_termek['id']]))
	{
		// ha ez volt az utolsó abból a termékből a felhasználó kosarában
		if(isset($kosar[$aktualis_termek['id']]) and $kosar[$aktualis_termek['id']]<2)
		{
			mysqli_query($link, "DELETE FROM kosar WHERE termek_id='" . $aktualis_termek['id'] . "' AND vasarlo_id='" . $vasarlo_id . "';");
			mysqli_query($link, "UPDATE termek SET raktaron=" . ($aktualis_termek['raktaron']+1) . " WHERE id=" . $aktualis_termek['id'] . ";");
		}
		else
		{
			mysqli_query($link, "UPDATE kosar SET termek_mennyiseg=" . mysqli_real_escape_string($link, $kosar[$aktualis_termek['id']]-1) . " WHERE termek_id=" . $aktualis_termek['id'] . ";");
			mysqli_query($link, "UPDATE termek SET raktaron=" . ($aktualis_termek['raktaron']+1) . " WHERE id=" . $aktualis_termek['id'] . ";");
		}
	}

	// a kosár teljes ürítése /////////////////////////////////
	if(isset($_POST['kosar_uritese']) and isset($kosar[$aktualis_termek['id']]))
	{
		mysqli_query($link, "UPDATE termek SET raktaron=" . ($aktualis_termek['raktaron']+$kosar[$aktualis_termek['id']]) . " WHERE id=" . $aktualis_termek['id'] . ";");
		mysqli_query($link, "DELETE FROM kosar WHERE termek_id='" . $aktualis_termek['id'] . "' AND vasarlo_id='" . $vasarlo_id . "';");
    }

	// a kosár tartalmának megvétele //////////////////////////
	if(isset($_POST['mindet_megveszem']) and isset($kosar[$aktualis_termek['id']]))
	{
		// vasarlo(felhasznalonev, jelszo, becsuletes_nev, szuletesi_datum)
		$vasarlo_adatok=mysqli_fetch_array(mysqli_query($link, "SELECT felhasznalonev, jelszo, becsuletes_nev, szuletesi_datum FROM vasarlo WHERE felhasznalonev = '" . $_SESSION['felhasznalonev'] . "';"));
		mysqli_query($link, "DELETE FROM kosar WHERE termek_id='" . $aktualis_termek['id'] . "' AND vasarlo_id='" . $vasarlo_id . "';");

	    $s = 'INSERT INTO vasarlas(idopont, vasarlo_becsuletes_neve, vasarlo_szuletesi_datuma, termek_id, termek_mennyiseg) ';
		mysqli_query($link, $s . 'VALUES(current_timestamp(), "' .$vasarlo_adatok['becsuletes_nev']. '", "' .$vasarlo_adatok['szuletesi_datum']. '", ' .$aktualis_termek['id']. ', ' .$kosar[$aktualis_termek['id']]. ');');
	}
}

}
?>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Főoldal</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Szerszámbolt</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php

// vásárlások
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/vasarlasok.php">Vásárlások</a></li>';

// felhasználók
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/vasarlok.php">Felhasználók</a></li>';

// beszállítás
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/beszallitas.php">Beszállitás</a></li>';

//bejelentkezés linkje
if(!isset($_SESSION['felhasznalonev'])){ echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/login.php">Bejelentkezés</a></li>';}

// regisztráció linkje
if(!isset($_SESSION['felhasznalonev'])){ echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/register.php">Regisztráció</a></li>';}

// a felhasználó adatainak módosítása (a jobb oldalon a gombok között)
if(isset($_SESSION['felhasznalonev'])){ echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/adatmodositas.php">Adatok módosítása</a></li>';}

// kijelentkezés
if(isset($_SESSION['felhasznalonev'])){	echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php?kijelentkezes=1">Kijelentkezés: ' . $_SESSION['felhasznalonev'] . '</a></li>';}

// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/index.php">Újratöltés</a></li>';

// a felhasználó törlése (a jobb oldalon a gombok között)
if(isset($_SESSION['felhasznalonev'])){ echo '<li id="torles" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/megerosites.php">A felhasználó törlése</a></li>';}


?>

</ul>
<!--</div>-->
</div>




<div id="test">


<!--------------------------- innentől kezdve a termékek táblázata található ----------------------------->

<!-- adatbázis megnyitása és az elérhető termékek adatainak lekérdezése -->

<?php
$eredmeny=mysqli_query($link, "SELECT id, termeknev, egysegar, raktaron FROM termek");
?>
	<br>
	<br>
	<br>
	<table id="termektablazat">
		<caption>Kínálat</caption>
		<tr>
			<th>Megnevezés</th>
			<th>Egységár</th>
			<th>Raktáron</th>
<?php
if(isset($_SESSION['felhasznalonev']))
{
	echo '<th></th>';
}
?>
		</tr>
	<?php while($row = mysqli_fetch_array($eredmeny)):?>
		<tr>
			<td><?=$row['termeknev'] ?></td>
			<td><?=$row['egysegar'] ?> Ft</td>
			<td><?=$row['raktaron'] ?> db</td>

<?php
if(isset($_SESSION['felhasznalonev']) and $row['raktaron']>0)
{
	echo '<form action="index.php" method="post">';
	echo '<td><input class="tablazat_gomb" type="submit" name="' . $row["id"] . '" value="Kosárba"></td>';
	echo "</form>";
}

if(isset($_SESSION['felhasznalonev']) and $row['raktaron']<=0)
{
	echo '<form>';
	echo '<td><input class="tablazat_gomb" type="submit" value="Elfogyott"></td>';
	echo "</form>";
}

?>

		</tr>
	<?php endwhile;?>

	</table>



<!-------------------------- ez itt a kosár táblázatát leíró rész -------------------------------->

<?php

if(isset($_SESSION['felhasznalonev']))
{
$eredmeny=mysqli_query($link, "SELECT termek_id, termek_mennyiseg FROM kosar WHERE vasarlo_id=" . $vasarlo_id . ";");

?>
	<table id="kosartablazat">
		<caption>Kosár</caption>
		<tr>
			<th>Termék</th>
			<th>Mennyiség</th>
			<th>Ár</th>
				<form action="index.php" method="post">
			<th><input class="tablazat_gomb" type="submit" name="kosar_uritese" value="Kiürítem a kosarat"></th>
				</form>
		</tr>
<?php
if($eredmeny!=false)
{
	$osszar=0;
while($row = mysqli_fetch_array($eredmeny)):
	$osszar=$osszar+$row['termek_mennyiseg']*$termek_tomb[$row['termek_id']]['egysegar'];
?>
		<tr>
			<td><?=$termek_tomb[$row['termek_id']]['termeknev'] ?></td>
			<td><?=$row['termek_mennyiseg'] ?> db</td>
			<td><?=$row['termek_mennyiseg']*$termek_tomb[$row['termek_id']]['egysegar']?></td>
				<form action="index.php" method="post">
			<td><input class="tablazat_gomb" type="submit" name=<?=$row['termek_id']?> value="Egyet visszateszek"></td>
				</form>
		</tr>
	<?php endwhile;?>

		<tr class="foot">
			<td><b>Összesen:</b></td>
			<td></td>
			<td><?=$osszar?> Ft</td>
				<form action="index.php" method="post">
			<td><input class="tablazat_gomb" type="submit" name="mindet_megveszem" value="Mindet megveszem"></td>
				</form>
		</tr>
	</table>

<?php }}?>
<!-------------------------------------------------------------------------------------->


</div> <!-- test vége -->

</body>
<?php mysqli_close($link);?>

</html>
