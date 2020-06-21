<?php

 function opendb(){
         $link = mysqli_connect("localhost", "root", "") or die("Connection errorom" . mysqli_error());
         mysqli_select_db($link, "nomk01");
         mysqli_query($link, "set_character_set_results='utf-8'");
         return $link;
 }

 ?>
