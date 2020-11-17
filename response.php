<?php


function getNextFile($data) {
    $filename = basename($data);
    $dirname = dirname($data);

    #$file_parts = explode("-", $filename);
    #$fileCount = $file_parts[1]; # intval(substr($filename, -5));
    #$newFileNumber = strval($fileCount +1);
    #$newFilename = $dirname . '/' . $file_parts[0] . '-' . $newFileNumber;
    $newFilename = strval(intval($filename) + 1);

    return $dirname . '/' . $newFilename;
    #return $newFilename;
}


function getPrevFile($data) {
    $filename = basename($data);
    $dirname = dirname($data);

    #$file_parts = explode("-", $filename);
    #$fileCount = $file_parts[1];
    #$fileCount = intval(substr($filename, -5));
    #$newFilename = $dirname . '/' . $file_parts[0] . '-' . $newFileNumber;
    $newFilename = strval(intval($filename) - 1);

    return $dirname . '/' . $newFilename;
    #return $newFilename;
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
