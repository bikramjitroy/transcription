<?php

$file_name = $_GET['filename'];
$file_content = file_get_contents($file_name . '.txt');
$read_file_content = file_get_contents($file_name . '.gl');
$meta_file_content = file_get_contents($file_name . '.meta');
$meta_data = explode(",", $meta_file_content);
$meta_male = explode(":", $meta_data[0]);
$meta_female = explode(":", $meta_data[1]);
$contents = json_encode (explode("\n\n", $read_file_content));
$content = '';
$trans_content = explode("\n", $file_content);
if(strlen($file_content)==0)
     $file_content = '0.0';
$last_time = end($trans_content);
if(false==is_numeric($last_time))
    $last_time = prev($trans_content);
$last_time = floatval($last_time);

$ReviewLink = 'Review.php'.'?filename='.$file_name;
#$file_name = 'recording_dir1/10001'; //This is the example one.
//Generate/Get wave file name without .wav extension format.


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
#echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Droid+Sans+Mono" rel="stylesheet">';
#echo '<script src="jquery.min.js"></script>';
#echo '<link href="highlight.css" rel="stylesheet">';
#echo '<link href="highlight-within-textarea/jquery.highlight-within-textarea.css" rel="stylesheet">';
#echo '<script src="highlight-within-textarea/jquery.highlight-within-textarea.js"></script>';
echo '</head>';




#echo '<body onload="higlight(lastSelectedText)">';
echo "<body>";

echo "<h5 style=\"margin-top:5px;margin-bottom:1px;\">File transcription page!</h5>";
echo "<br>";
echo "<p style=\"font-size:12px;margin-right:100px;\"> File Name : \"$file_name.wav\" </p>";

echo "<script>";
echo "       function reviewPage() {";
echo "            window.open(\"$ReviewLink\");";
echo "}";
echo "</script>";

echo "<button type=\"button\" onclick=\"reviewPage()\">GoToReviewTool</button>";

echo " <br>";

echo "<audio id=\"audioFileId\" controls style=\"width:800px;margin-right:10px;\">";
echo   "<source src=\"$file_name.wav\" type=\"audio/wav\">";
echo  "Your browser does not support the audio tag.";
echo "</audio>";

echo "<input type=\"text\" name=\"curTime\" id=\"curTime\" style=\"width:100px;height:30px;font-size:25px;\"   value=0.0>Current Time</input>";

#echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">


echo '
      <script> 
 

     var audio = document.getElementById("audioFileId");
     setInterval(function(){ document.getElementById("curTime").value = audio.currentTime; }, 500);

      window.onkeyup = function(e) {
      
         //Left - prev
         if (e.ctrlKey && e.keyCode == 37) {
             previousFile();
         }

         //Up - Replay
         if (e.ctrlKey && e.keyCode == 37) {
             replay();
         }
	 if ( e.keyCode == 115) {
             replay();
         }

         //Right - Skip
         if (e.ctrlKey && e.keyCode == 39) {
              forward();
         }

         //Down - Save
         if (e.ctrlKey && e.keyCode == 40) {
             saveFile();
         }
         if(e.ctrlKey && e.keyCode == 190) {
            nextLine();
         }
	 if(e.ctrlKey && e.keyCode == 188) {
            prevLine();
         }
         if ( e.ctrlKey && e.keyCode === 89) { //start
	   audio.currentTime = getLastTransTime();
	    audio.play();
         } 
	 if ( e.keyCode == 113) {
             pasteSelection();
         }

         if ( e.keyCode == 27) {
             if(audio.paused){
               audio.play();
            }else {
               audio.pause();
            }
         }

	 if ( e.ctrlKey && e.keyCode === 77) {
	     audio.play();
         }
	 if ( e.ctrlKey && e.keyCode === 112) {
             audio.play();
         }
         if (e.ctrlKey && e.keyCode === 88) { //pause button keycode
            audio.pause();
         }

	 if ( e.ctrlKey && e.keyCode === 221) {
            var speed = audio.playbackRate;
            audio.playbackRate = speed + 0.1;
         }
         if ( e.ctrlKey && e.keyCode === 219) {
            var speed = audio.playbackRate;
            audio.playbackRate = speed - 0.1;
         }

 	    if (e.ctrlKey && e.keyCode ==32) {
	    //nextLine();
	}

      }
      
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
echo     "post('response.php', params, 'POST', responseCallback);";
echo    ' }

    function SaveFile() {';
echo     "var params = '{\"command\": \"SaveFile\", \"filename\": \"$file_name\", \"textData\": ' + JSON.stringify(document.getElementById(\"textId\").value) + '}';";
echo     "post('response.php', params, 'POST', responseCallback);";
#echo     "var params2 = '{\"command\": \"MetaData\", \"filename\": \"$file_name\", \"MaleSpeakers\": ' +  JSON.stringify(document.getElementById(\"malespeakers\").value) + ',\"FemaleSpeakers\": ' + JSON.stringify(document.getElementById(\"femalespeakers\").value) + '}';";
#echo     "post('response2.php', params2, 'POST', responseCallback);";
echo    ' }
    

      function previousFile() {';
echo     "var params = '{\"command\": \"PreviousFile\", \"filename\": \"$file_name\"}';";
echo     "post('response.php', params, 'POST', responseCallback)";
echo    ' }

      function skipCurrentFile() {';
echo     "var params = '{\"command\": \"SkipCurrentFile\", \"filename\": \"$file_name\"}';";
echo     "post('response.php', params, 'POST', responseCallback)";
echo    ' }

      function responseCallback(data) {
         //location.href = location.protocol + "//" + location.host + location.pathname + "?filename=" + data; 

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
     
    var currTime =currentLastTime;
     function pasteSelection() {
	audio.pause();
	currentLastTime = getLastTransTime();
        lastSelectedText = selectText();
	var radios = document.getElementsByName("correct_audio");
	var gender = "";
	if(radios[1].checked)
		 gender = "11-BadAudio ";
	if(audio.currentTime - currentLastTime < 5  && (gender == ""))  {
		alert("timestamp diff is less than 5seconds ");
		return;
	    }
	if( audio.currentTime - currentLastTime >15 && (gender == "")) {
		alert("timestamp diff is more than 15seconds");
                return;
         }
       content  = document.getElementById("textId").value + "\n"+  gender +  lastSelectedText + "\n" + audio.currentTime;

       lastSelectedText = "";	
       selectedText.push(lastSelectedText);
       document.getElementById("correct").checked = true; 
       textarea = document.getElementById("textId");
       textarea.value = content ;
       currTime = audio.currentTime;
       SaveFile()
       textarea.scrollTop = textarea.scrollHeight;
       document.getElementById("readTextId").selectionStart = document.getElementById("readTextId").selectionEnd ;
       //document.getElementById("readTextId").selectionEnd = 0;

     }
     function playAt() {
	var playTime = document.getElementById("playtime").value;
          audio.currentTime = parseFloat(playTime);
	  audio.play();
     }

     var start = 0;
     var end = 0;	
     function selectText()
      {
                txtarea = document.getElementById("readTextId");
		if(txtarea.selectionStart == txtarea.selectionEnd) {
			return "";	
		}
                start = txtarea.selectionStart;
                end = txtarea.selectionEnd;
                var sel = txtarea.value.substring(start, end);
                return sel;
      }

     function lastSelect () {
                txtarea = document.getElementById("readTextId");
                txtarea.focus();
                txtarea.selectionStart = start;
                txtarea.selectionEnd  = end;

     }

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
       function speedUp() {
  	  var speed = audio.playbackRate;
            audio.playbackRate = speed + 0.1;

       }
       function speedDown() {
           var speed = audio.playbackRate;
            audio.playbackRate = speed - 0.1;

       }

       function adjustFont(size){
	   //var size = document.getElementById("fontid").value;
	   //var size = parseInt(document.getElementById("readTextId").style.fontSize) + 1;
	   size = size *1.5;
	   document.getElementById("readTextId").style.fontSize = size.toString(); 
           //var size2 = parseInt(document.getElementById("textId").style.fontSize) + 1;
           document.getElementById("textId").style.fontSize = size.toString(); 
	}

	function fontDecreaseAuto() {
	  var size = parseInt( document.getElementById("readTextId").style.fontSize) -1 ; 
	  document.getElementById("readTextId").style.fontSize = size.toString();
          var size2 = parseInt( document.getElementById("textId").style.fontSize) -1 ; 
          document.getElementById("textId").style.fontSize = size2.toString();
	}

	function fontIncreaseManual(){
           var size = parseInt(document.getElementById("textId").style.fontSize) + 1;
           document.getElementById("textId").style.fontSize = size.toString(); 
        }

        function fontDecreaseManual() {
          var size = parseInt( document.getElementById("textId").style.fontSize) -1 ; 
         document.getElementById("textId").style.fontSize = size.toString();
        }
	function adjustFontType(font) {
		document.getElementById("readTextId").style.fontFamily = font;
		document.getElementById("textId").style.fontFamily = font;
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


echo  "<br>";
echo "<button type=\"button\" onclick=\"getCurrTime()\">getTime</button>";
echo "<textarea id=\"timeId\" cols=\"10\" rows=\"1\" readonly>0</textarea>";
echo "<input type=\"text\" name=\"time\" id=\"playtime\" style=\"margin-left:5px\" size=\"10\" placeholder=\"time\">";
echo "<button type=\"button\" onclick=\"playAt()\">Play</button>";
echo  "<br>";

echo  "<h5 style=\"margin-top:3px;margin-bottom:1px; margin-right:50px;\">Automatic Transcript</h5>";
#echo "font-adjust: <button type=\"button\" id=\"font-increase\" onclick=\"adjustFont()\">+ </button>";
echo "font-size: ";
echo ' <select id="fontid" style="margin-right:50px;" onchange="adjustFont(this.value)">
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  <option value="13">13</option>
  <option value="14">14</option>
  <option value="16">16</option>
  <option value="18">18</option>
</select>';

echo "font-type";
echo ' <select id="fontid" onchange="adjustFontType(this.value)">
  <option value="Times New Roman">Times New Roman</option>
  <option value="Arial">Arial</option>
  <option value="Calibri">Calibri</option>
</select>';

#echo "<button type=\"button\" id=\"font-decrease\" onclick=\"fontDecreaseAuto()\"> - </button>";
echo  "<textarea class=\"read-text\" id=\"readTextId\" style=\"width:100%;height:30%;font-size:18px;font-family:'Times New Roman'\"  readonly>$read_file_content</textarea>";
echo  "</br>";
echo "<button type=\"button\" id=\"select\" onclick=\"pasteSelection()\">PasteSelection(ctrl+Enter/F2)</button>";
echo "<button type=\"button\" onclick=\"pause()\">Pause/Resume(Esc)</button>";
echo "<button type=\"button\" onclick=\"play()\">PlayLastSeg(ctrl+y)</button>";
#echo "<button type=\"button\" onclick=\"speedUp()\">SpeedUp(ctrl+])</button>";
#echo "<button type=\"button\" onclick=\"speedDown()\">SpeedDown(ctrl+[ )</button>";
echo "<button type=\"button\" onclick=\"replay()\">Back2Sec(Ctrl+Left/F4)</button>";
echo "<button type=\"button\" onclick=\"forward()\">Forward2Sec(Ctrl+Right)</button>";
echo "<button type=\"button\" style=\"margin-left:5px;\"  onclick=\"SaveFile()\">SaveFile(Ctrl+Down)</button>";
echo "<button type=\"button\" style=\"margin-left:5px;\" onclick=\"lastSelect()\">GoToLastSelection</button>";
echo  "<br>";
echo  "<br>";
echo "<input type=\"radio\" id=\"correct\" name=\"correct_audio\" checked value=\"Correct\"> Correct Audio";
echo "<input type=\"radio\" id=\"wrong\" name=\"correct_audio\" value=\"Wrong\"> Overlapping voice/Music/Noise/Silence(11)";

echo  "<br>";
echo  "<h5 style=\"margin-top:5px;margin-bottom:1px;margin-right:50px;\">Manual Transcript</h5>";
#echo "font-adjust: <button type=\"button\"  onclick=\"fontIncreaseManual()\">+ </button>";
#echo "<button type=\"button\"  onclick=\"fontDecreaseManual()\"> - </button>";

echo  "<textarea id=\"textId\" style=\"width:100%;height:35%;font-size:18px;font-family:'Times New Roman'\"  autofocus>$file_content</textarea>";

echo  "</br>";
#echo "<input type=\"text\" name=\"malespeakers\" id=\"malespeakers\" size=\"18\" placeholder=\"Num of Male speaker\">";
# if(strlen($meta_male[1]) >0) 
#echo "Num of Male speaker : <input type=\"text\" name=\"malespeakers\" id=\"malespeakers\" size=\"5\" style=\"margin-right:5px;\" value=$meta_male[1]>";
#if(strlen($meta_female[1]) >0)
#echo "Num of Female speakers: <input type=\"text\" name=\"femalespeakers\" id=\"femalespeakers\" size=\"5\"  value=$meta_female[1]>";
#echo "<input type=\"text\" name=\"femalespeakers\" id=\"femalespeakers\" size=\"18\" placeholder=\"Num of Female speaker\">";
echo "<button type=\"button\" onclick=\"previousFile()\" style=\"display:none\">PreviousFile(Ctrl+Left)</button>";
echo "<button type=\"button\" onclick=\"skipCurrentFile()\"style=\"display:none\">SkipCurrentFile(Ctrl+Right)</button>";



echo "</body>";
echo "</html>";

?>

