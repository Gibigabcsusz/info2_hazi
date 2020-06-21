<!DOCTYPE html>
<?php session_start();

	include 'db.php';
	$link=opendb();
	$eredmeny=mysqli_query($link, "SELECT felhasznalonev, jelszo, becsuletes_nev, szuletesi_datum FROM vasarlo");
	mysqli_close($link);
?>


<head>
    <link rel="stylesheet" href="styles.css">
	<title>Vásárlók</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Vásárlók adatai</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/vasarlok.php">Újratöltés</a></li>';

?>

</ul>

</div>


		<table id="adatok">
			<tr>
				<th class="adat"><b>Felhasználónév</b></td>
				<th class="adat">Jelszó</td>
				<th class="adat">Becsületes Név</td>
				<th class="adat">Születési Dátum</td>
			</tr>
			<?php while($row = mysqli_fetch_array($eredmeny)): ?>
        	 <tr>
             	<td class="adat"><?=$row['felhasznalonev']?></td>
             	<td class="adat"><?=$row['jelszo']?></td>
             	<td class="adat"><?=$row['becsuletes_nev']?></td>
             	<td class="adat"><?=$row['szuletesi_datum']?></td>
         	</tr>
			<?php endwhile; ?>
		</table>
	</p>


</body>

</html>
