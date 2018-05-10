<?php
function serverTime($string = null){
  $date = date('d.m.Y');
  $time = date('H:i:s', time());
  $dateTime = date('d.m.Y H:i:s', time());

  if ($string == "date"){
    return $date;
  } elseif ($string == "time") {
    return $time;
  } else {
    return $dateTime;
  }
}
?>
