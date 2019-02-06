<?php
function getPeopleResultscheck($get){
  if (isset($get) && isset($get["sex"])){
    return $get["sex"]=="Female" || $get["sex"]=="Male" || $get["sex"]="Unknown";
  }
}

?>
