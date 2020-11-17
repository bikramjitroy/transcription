<?php

$file_name = $_GET['filename'];
$file_content = file_get_contents($file_name . '.txt');
$read_file_content = file_get_contents($file_name . '.gl');
$contents = json_encode (explode("\n\n", $read_file_content));
$content = '';
$trans_content = explode("\n", $file_content);
if(strlen($file_content)==0)
     $file_content = ' ';
$last_time = end($trans_content);
if(false==is_numeric($last_time))
    $last_time = prev($trans_content);
$last_time = floatval($last_time);

$basename_file=basename($file_name);
$pieces = explode("_", $basename_file);
$file_orig=$pieces[0];
$start_time=intval($pieces[1]);

echo "<html>";
echo '<script>
      var lastSelectedText = "";
      function  higlight (lastSelectedText) {
           $(".read-text").highlightWithinTextarea({
                highlight: lastSelectedText
           });
           $(".read-text").highlightWithinTextarea("update");
      }
</script>';

echo '<head>';
echo $file_name;
echo '</head>';




echo "<body>";

echo " <br>";

echo "<audio id=\"audioFileId\" controls style=\"width:800px;margin-right:10px;\" autoplay>";
echo   "<source src=\"$file_name.wav\" type=\"audio/wav\">";
echo  "Your browser does not support the audio tag.";
echo "</audio>";

#echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">


echo '
      <script> 
 

     var audio = document.getElementById("audioFileId");
     
      window.onkeypress = function(event) {
       if (event.ctrlKey && event.which == 10) {       
           event.preventDefault();
	   pasteSelection();
         }
         }

      function replay() {
          curTime = audio.currentTime;
            time = curTime - 2;
          document.getElementById(\'audioFileId\').currentTime= parseFloat(time); 
          document.getElementById(\'audioFileId\').play();
      }
      function forward() {
        curTime = audio.currentTime;
            time = curTime +2 ;
          document.getElementById(\'audioFileId\').currentTime= parseFloat(time); 
          document.getElementById(\'audioFileId\').play();

      }

      function getCurrTime() {
        var currentTime = audio.currentTime;
        document.getElementById(\'timeId\').value = currentTime;
      }

    function getLastTransTime() {
	textLines = document.getElementById("textId").value.split("\n");
	
       return parseFloat(textLines.pop())  ;
     }

      function post(url, params, method, callback) {
          method = method || "POST"; // Set method to post by default if not specified.

	  //params = JSON.stringify(params);
	  params = params.replace(/\\n/g, "\\n");
          var http = new XMLHttpRequest();
          http.open(method, url, true);
          
          //Send the proper header information along with the request
          http.setRequestHeader("Content-type", "application/json");
          
          http.onreadystatechange = function() {//Call a function when the state changes.
              if(http.readyState == 4 && http.status == 200) {
                  callback(http.responseText);
              }
          }
          http.send(params);
      }


      function saveAndNextFile() {';
echo     "var params = '{\"command\": \"SaveAndNextFile\", \"filename\": \"$file_name\", \"textData\": ' + JSON.stringify(document.getElementById(\"textId\").value) + '}';";
echo     "post('mobile_response_N.php', params, 'POST', responseCallback);";
echo    ' }

    function SaveFile() {';
echo     "var params = '{\"command\": \"SaveFile\", \"filename\": \"$file_name\", \"textData\": ' + JSON.stringify(document.getElementById(\"textId\").value) + '}';";
echo     "post('mobile_response_N.php', params, 'POST', responseCallback);";
echo    ' }
    

      function previousFile() {';
echo     "var params = '{\"command\": \"PreviousFile\", \"filename\": \"$file_name\"}';";
echo     "post('mobile_response_N.php', params, 'POST', responseCallback)";
echo    ' }

      function skipCurrentFile() {';
echo     "var params = '{\"command\": \"SkipCurrentFile\", \"filename\": \"$file_name\"}';";
echo     "post('mobile_response_N.php', params, 'POST', responseCallback)";
echo    ' }

      function responseCallback(data) {
         location.href = location.protocol + "//" + location.host + location.pathname + "?filename=" + data; 

         //alert(data);
      }';

echo  'var time = '.$start_time.';';

echo  'function skipNextFiveMin() {
         time = time+300;

         var file = "'.$file_orig.'";
         var time_start = time;
         var time_end = time + 30;
         var data = "NPTEL/Nayan/" + file + "/" + file + "_" + time_start + "_" + time_end; 
         location.href = location.protocol + "//" + location.host + location.pathname + "?filename=" + data; 

         //alert(data);
      }';


echo "var textcontents = $contents;";
echo   'var content = "";
      var i = 0;';
echo "    var currentLastTime = $last_time;";
echo ' 
     function prevLine() {
      if(i>0) {
        i -= 1;
        document.getElementById("readTextId").scrollTop = 15*i ;
      }

     }
     
    function nextLine() {
       i += 1;
        document.getElementById("readTextId").scrollTop = 15*i ;

    }
   var selectedText =[];
     
      function play() {
          audio.currentTime = getLastTransTime();
          audio.play();
       }
       function pause() {
	   if(audio.paused){
               audio.play();
            }else {
               audio.pause();
            }

       }

      </script>
   <style>
    h5 {
  display: inline-block;
}
     p{
     display: inline-block;
  }
  </style>


     ';


echo  "<textarea class=\"read-text\" id=\"readTextId\" style=\"width:100%;height:5%;font-size:18px;font-family:'Times New Roman'\"  readonly>$read_file_content</textarea>";
echo  "</br>";
echo "<button type=\"button\" onclick=\"pause()\">Pause/Resume(Esc)</button>";
echo "<button type=\"button\" onclick=\"replay()\">Back2Sec</button>";
echo "<button type=\"button\" onclick=\"forward()\">Forward2Sec</button>";
echo  "<br>";
echo  "<br>";
echo  "<h5 style=\"margin-top:5px;margin-bottom:1px;margin-right:50px;\">Manual Transcript</h5>";


if (trim($file_content) == false) {
    echo  "<textarea id=\"textId\" style=\"width:100%;height:5%;font-size:18px;font-family:'Times New Roman';background-color: lightblue;\"  autofocus>$read_file_content</textarea>";
} else {

    echo  "<textarea id=\"textId\" style=\"width:100%;height:5%;font-size:18px;font-family:'Times New Roman';\"  autofocus>$file_content</textarea>";
}

echo  "</br>";
echo "<button type=\"button\" onclick=\"previousFile()\">PreviousFile</button>";
echo "<button type=\"button\" onclick=\"skipNextFiveMin()\">Skip Next 5 Min</button>";
echo "<button type=\"button\" onclick=\"skipCurrentFile()\">Go Next File</button>";

#echo "<button type=\"button\" style=\"margin-left:10px;\"  onclick=\"SaveFile()\">SaveFile</button>";
echo "<button type=\"button\" style=\"margin-left:10px;\"  onclick=\"saveAndNextFile()\">SaveAndNextFile</button>";


echo "</body>";
echo "</html>";

?>

