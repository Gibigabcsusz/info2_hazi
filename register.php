<!DOCTYPE html>
<?php session_start();?>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Regisztráció</title>
</head>

<body>

<?php
$visszajelzes="";

if(isset($_GET['try']) and $_GET['try']==1){

	include 'db.php';
	$link=opendb();

	$jo_fn=(int)((isset($_POST['felhasznalonev'])) and ($_POST['felhasznalonev']!=""));
	$jo_j=(int)(isset($_POST['jelszo']) and $_POST['jelszo']!="");
	$jo_ju=(int)(isset($_POST['jelszo_ujra']) and $_POST['jelszo_ujra']!="");
	$jo_bn=(int)(isset($_POST['becsuletes_nev']) and $_POST['becsuletes_nev']!="");
	$jo_szd=(int)(isset($_POST['szuletesi_datum']) and $_POST['szuletesi_datum']!="");

	

// a felhasználónév ellenőrzése, hogy meg van-e adva és fel van-e már használva

	if($jo_fn)
	{
		$query = sprintf('select felhasznalonev from vasarlo where felhasznalonev="%s";', mysqli_real_escape_string($link, $_POST['felhasznalonev']));
		$eredmeny = mysqli_fetch_array(mysqli_query($link, $query));
		if(isset($eredmeny['felhasznalonev']) and $eredmeny['felhasznalonev']==$_POST['felhasznalonev'])
		{
			$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>A felhasználónév már foglalt!</b></p>";
		}
	}
	else
	{
		$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>Nem adtál meg felhasználónevet!</b></p>";
	}

// jelszó ellenőrzése

	if(!($jo_j))
	{
		$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>Nem adtál meg jelszót!</b></p>";
	}

// jelszó megerősítés ellenőrzése

	if($jo_ju)
	{
		if($_POST['jelszo']!=$_POST['jelszo_ujra'])
		{
			$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>A megadott jelszavak nem egyeznek!</b></p>";
		}
	}
	else
	{
		$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>Nem erősítetted meg a jelszavad!</b></p>";
	}

// becsületes név ellenőrzése

	if(!($jo_bn))
	{
		$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>Nem adtad meg a becsületes nevedet!</b></p>";
	}

// dátum ellenőrzése

	if(!($jo_szd))
	{
		$visszajelzes = $visszajelzes . "<br><p style='color:red;'><b>Nem adtál meg születési dátumot!</b></p>";
	}

	if($visszajelzes=="")
	{
		$a=mysqli_real_escape_string($link, $_POST['felhasznalonev']);
		$b=mysqli_real_escape_string($link, $_POST['jelszo']);
		$c=mysqli_real_escape_string($link, $_POST['becsuletes_nev']);
		$d=mysqli_real_escape_string($link, $_POST['szuletesi_datum']);

		$query = sprintf("INSERT INTO vasarlo(felhasznalonev, jelszo, becsuletes_nev, szuletesi_datum) VALUES('%s', '%s', '%s', '%s');", $a, $b, $c, $d);
		mysqli_query($link, $query);
		mysqli_close($link);

		$_SESSION['felhasznalonev']=$_POST['felhasznalonev'];
		header("Location: index.php");
	}
}
?>



<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Regisztráció</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/register.php">Újratöltés</a></li>';

?>

</ul>

</div>

<div id="test">
	<div id="form_blokk">
	<form action="register.php?try=1" method="post">
			<p>
				Felhasználónév:<br><input type="text" name="felhasznalonev" />
 			</p>
 			<p>
				Jelszó:<br><input type="password" name="jelszo" />
 			</p>
 			<p>
				Jelszó újra:<br><input type="password" name="jelszo_ujra" />
 			</p>
			<p>
				Becsületes név:<br><input type="text" name="becsuletes_nev" />
 			</p>
 			<p>
				Születési dátum: <br><input type="date" name="szuletesi_datum" />
 			</p>
 			<p>
 				<input type="submit" value="Regisztrálok!" name="keres" />
 			</p>
	</form>
	</div>

<div id="hibauzenet_blokk">
	<?php echo $visszajelzes;?>
</div>



</div>

</body>
</html>
