<!DOCTYPE html>
<?php session_start();?>
<html>

<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Beszállítás</title>
</head>

<body>

<div id="fejlec">
<!------------------------------- innentől a cím található----------------------------------------->
<div id="cim">  <!-- cím -->
	<h1>Beszállítás</h1>

</div>          <!--  cím vége  -->

<!--------------------------- innentől kezdve a menü található ----------------------------->


<!--<div id="menu">-->
<ul id="menu">

<?php
// főoldal linkje
echo '<li class="menu_gomb"><a href="http://localhost/nagyhazi/index.php">Vissza a kezdőlapra</a></li>';


// újratöltés (a jobb oldalon a gombok között)
echo '<li id="ujra" class="menu_jobb_gomb"><a href="http://localhost/nagyhazi/beszallitas.php">Újratöltés</a></li>';

?>

</ul>

</div>

<div id="test">

	<form action="beszallitas.php?try=1" method="post">

            <p>
                Termék neve:<br><input type="text" name="beszallitott_termek" />
            </p>
            <p>
                Darab:<br><input type="number" name="beszallitott_darab" />
            </p>
            <p>
                Egységár (Ft):<br><input type="number" name="beszallitott_egysegar" />
            </p>
            <p>
                <input type="submit" value="Beszállítok!" name="beszallitas" />
            </p>
    </form>

<?php
include 'db.php';
$link=opendb();

if(isset($_GET['try']) and $_GET['try']==1 and isset($_POST['beszallitas']))
{
    $eredmeny=mysqli_query($link, "SELECT id, termeknev, raktaron, egysegar FROM termek");
	$i=0;
    while($row=mysqli_fetch_array($eredmeny))
    {
        if($row['termeknev']==$_POST['beszallitott_termek'])
        {
			mysqli_query($link, "UPDATE `termek` SET `raktaron` = " . ($row['raktaron']+$_POST['beszallitott_darab']+0) . ", `egysegar` = ".$_POST['beszallitott_egysegar']." WHERE `termeknev` = '" . $row['termeknev'] . "';");
			$i=1;
			//mysqli_query($link, "UPDATE `termek` SET `egysegar` = ".($row['egysegar']+$).", `raktaron` = 1 WHERE `termeknev`= '".$row['termeknev']."';");
			echo "<h1>" . $row['egysegar'] . "</h1>";
			header("Location: index.php");
        }
    }
	if($i==0)
	{
		mysqli_query($link, "INSERT INTO termek(termeknev, egysegar, raktaron) VALUES('" . $_POST['beszallitott_termek'] . "', " . $_POST['beszallitott_egysegar'] . ", " . $_POST['beszallitott_darab'] . ");");
		header("Location: index.php");
	}
}



?>

</div>

</body>
</html>



