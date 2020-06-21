<!DOCTYPE html>
<?php session_start();?>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Bejelentkezés</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Bejelentkezés</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/login.php">Újratöltés</a></li>';

?>

</ul>

</div>

<div id="test">



	<form action="login.php" method="post">
			<p>
				<div class="magyarazat">Felhasználónév: </div><input type="text" name="felhasznalonev" />
 			</p>
 			<p>
				<div class="magyarazat">Jelszó: </div><input type="text" name="jelszo" />
 			</p>
 			<p>
 				<input type="submit" value="Bejelentkezés" name="keres" />
 			</p>
 	</form>

	<!-- helytelen felhasználónév esetén ez a rész jelzi ki a megfelelő hibaüzenetet -->
	<?php if(isset($_GET['rossznev']) and $_GET['rossznev']==1){echo "<p class='hibauzenet'><b>Helytelen felhasználónév</b></p>";}?>
	<?php if(isset($_GET['rosszjelszo']) and $_GET['rosszjelszo']==1){echo "<p class='hibauzenet'><b>Helytelen jelszó</b></p>";}?>

</div>

</body>

<!html>

<?php
	include 'db.php';
	if(isset($_POST['felhasznalonev']) and isset($_POST['jelszo']) and $_POST['felhasznalonev'] and $_POST['jelszo'])
	{
		$link=opendb();
		$query = sprintf('select felhasznalonev, jelszo from vasarlo where felhasznalonev="%s";', mysqli_real_escape_string($link, $_POST['felhasznalonev']));
		$eredmeny = mysqli_fetch_array(mysqli_query($link, $query));
		mysqli_close($link);

		if (isset($eredmeny['felhasznalonev']) and $eredmeny['felhasznalonev'] and isset($eredmeny['jelszo']) and $eredmeny['jelszo']==$_POST['jelszo'])
		{
			session_start();
			$_SESSION['felhasznalonev']=$eredmeny['felhasznalonev'];
			header("Location: index.php?");
		}
		else if (isset($eredmeny['felhasznalonev']) and $eredmeny['felhasznalonev'] and $eredmeny['jelszo']!=$_POST['jelszo'])
		{
			header("Location: login.php?rosszjelszo=1");
		}
		else
		{
			header("Location: login.php?rossznev=1");
		}
	}
?>
