<!DOCTYPE html>
<?php session_start();?>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
	<title>Értesítés</title>
</head>

<body>

<div id="fejlec">
	<div id="cim">

<?php
if(isset($_SESSION['felhasznalonev']) and isset($_GET['siker']))
{
	if($_GET['siker']==1)
	{
		echo "<h1>A felhasználói fiók törlése sikeres volt.</h1>";
		session_unset();
		session_destroy();
	}
	else
	{
		echo "<h1>Nem sikerült törölni a felhasználót.</h1>";
	}
}
else
{
	header("Location: index.php");
}?>
	</div>
	<ul id="menu"></ul>
</div>

<p style="text-align:center;"><a id="OK_gomb" href="http://localhost/nagyhazi/index.php">OK</a></p>

</body>

</html>
