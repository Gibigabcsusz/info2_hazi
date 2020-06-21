<!DOCTYPE html>
<?php session_start();

	include 'db.php';
	$link=opendb();
	$eredmeny=mysqli_query($link, "SELECT idopont, vasarlo_becsuletes_neve, vasarlo_szuletesi_datuma, termek_id, termek_mennyiseg FROM vasarlas");

	$termek_eredmeny=mysqli_query($link, "SELECT id, raktaron, termeknev, egysegar FROM termek");

	// 2D array a raktáron lévő termékek tárolására $termek_tomb: [ id => array(id, termeknev, raktaron, egysegar) ]

	$termek_tomb=array();
	while($row=mysqli_fetch_array($termek_eredmeny))
	{
		$termek_tomb[$row['id']]=array('termeknev'=>$row['termeknev'], 'raktaron'=>$row['raktaron'], 'id'=>$row['id'], 'egysegar'=>$row['egysegar']);
	}
?>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Vásárlások</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Vásárlások</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/vasarlasok.php">Újratöltés</a></li>';

?>

</ul>

</div>
		<table id="adatok">
			<tr>
				<th class="adat"><b>Vásárlás időpontja</b></td>
				<th class="adat">Vásárló Neve</td>
				<th class="adat">Vásárló Születési Dátuma</td>
				<th class="adat">Termék</td>
				<th class="adat">Mennyiség</td>
			</tr>
			<?php while($row = mysqli_fetch_array($eredmeny)): ?>
        	 <tr>
             	<td class="adat"><?=$row['idopont']?></td>
             	<td class="adat"><?=$row['vasarlo_becsuletes_neve']?></td>
             	<td class="adat"><?=$row['vasarlo_szuletesi_datuma']?></td>
             	<td class="adat"><?=$termek_tomb[$row['termek_id']]['termeknev']?></td>
             	<td class="adat"><?=$row['termek_mennyiseg']?></td>
         	</tr>
			<?php endwhile; ?>
		</table>
	</p>

	<?php mysqli_close($link);?>

</body>

</html>
