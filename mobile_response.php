<?php

$plus = 27;
$duration = 29;

function getNextFile($data) {
    $filename = basename($data);
    $dirname = dirname($data);

    $pieces = explode("_", $filename);
    $next_start_time = intval($pieces[1]) + $GLOBALS['plus'];
    $next_end_time = $next_start_time + $GLOBALS['duration'];
    $newFilename = $pieces[0].'_'.strval($next_start_time).'_'.strval($next_end_time);

    return $dirname . '/' . $newFilename;
    #return $newFilename;
}


function getPrevFile($data) {
    $filename = basename($data);
    $dirname = dirname($data);

    $pieces = explode("_", $filename);
    $next_start_time = intval($pieces[1]) - $GLOBALS['plus'];
    $next_end_time = $next_start_time + $GLOBALS['duration'];
    $newFilename = $pieces[0].'_'.strval($next_start_time).'_'.strval($next_end_time);

    return $dirname . '/' . $newFilename;

}



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));

  if (strcmp($data->command, 'SkipCurrentFile') == 0)
  {
      echo getNextFile($data->filename);
  }
  else if (strcmp($data->command, 'PreviousFile') == 0) 
  {
      echo getPrevFile($data->filename);
  }
  else if (strcmp($data->command, 'SaveAndNextFile') ==0) 
  {
      # Do not save data if no text in file
      if (strlen (trim($data->textData)) > 0) {
          $filename = $data->filename . ".txt";
          $myfile = fopen($filename, "w") or die("Unable to open file! name: " . $filename);
          fwrite($myfile, $data->textData);
          fclose($myfile);
      }
      echo getNextFile($data->filename);
  }else if(strcmp($data->command, 'SaveFile') ==0) 
  {
      #if (strlen (trim($data->textData)) > 0) {
	{
            $filename = $data->filename . ".txt";
            $myfile = fopen($filename, "w") or die("Unable to open file! name: " . $filename);
            fwrite($myfile, $data->textData);

      }
  }
  else {
      echo "NoMatchfgg";
  }
} else {
      echo "NotRec";
}

?>
