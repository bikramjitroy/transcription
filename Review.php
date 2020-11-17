<?php

$file_name = $_GET['filename'];
$file_content = file_get_contents($file_name . '.txt');
$read_file_content = file_get_contents($file_name . '.gl');
$contents = json_encode (explode("\n\n", $read_file_content));
$content = '';
$trans_content = explode("\n", $file_content);
$file_content_array = json_encode (explode("\n",$file_content));
$last_time = end($trans_content);
if(false==is_numeric($last_time))
    $last_time = prev($trans_content);
$last_time = floatval($last_time);

$meta_file_content = file_get_contents($file_name . '.meta');
#$meta_data = explode(":", $meta_file_content);
$doubt = $meta_file_content;


$TranscriptionLink = 'Transcription.php'.'?filename='.$file_name;
#$file_name = 'recording_dir1/10001'; //This is the example one.
//Generate/Get wave file name without .wav extension format.


echo "<html>";
echo "<body>";

echo "<h4>File Review page!</h4>";
echo "<pstyle=\"font-size:12px;margin-right:50px;\"> File Name : \"$file_name.wav\" </p>";

echo "<script>";
echo "       function transcriptionPage() {";
echo "            window.open(\"$TranscriptionLink\");";
echo "}";
echo "</script>";

echo "<button type=\"button\" onclick=\"transcriptionPage()\">GoToTranscriptionTool</button>";

echo "<audio id=\"audioFileId\" controls style=\"width:800px\">";
echo   "<source src=\"$file_name.wav\" type=\"audio/wav\">";
echo  "Your browser does not support the audio tag.";
echo "</audio>";

echo "<input type=\"text\" name=\"curTime\" id=\"curTime\" style=\"width:100px;height:30px;font-size:25px;\"   value=0.0>Current Time</input>";

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

         //Right - Skip
         if (e.ctrlKey && e.keyCode == 39) {
              forward();
         }

         //Down - Save
         if (e.ctrlKey && e.keyCode == 40) {
             SaveFile();
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
             //audio.play();
              //pasteSelection();
	     playNext();
         }
         if ( e.keyCode == 115) {
             //audio.currentTime = getLastTransTime();
 	      playCurrent();
             //audio.play();
         }
         if ( e.keyCode == 118) {
 	     playPrev();
	}
         if ( e.keyCode == 27) {
             if(audio.paused){
               audio.play();
            }else {
               audio.pause();
            }
         }

         if ( e.ctrlKey && e.keyCode === 73) {
           getCurrTime(); 
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
           //pasteSelection();
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
       return currentLastTime  ;
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

echo     "filecontents = document.getElementById(\"textId\").value.split(\"\\n\");";

echo     '}
  	function SaveDoubt() {';
echo     "var params2 = '{\"command\": \"MetaData\", \"filename\": \"$file_name\", \"doubt\": ' +  JSON.stringify(document.getElementById(\"doubt\").value) + '}';";
echo     "post('response2.php', params2, 'POST', responseCallback);";

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
echo "var filecontents = $file_content_array;";
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

    function hasNumbers(t)
    {
	var regex = /\d/g;
	return regex.test(t);
    }   

    function checkNumber() {
	contents  = document.getElementById("textId").value.split("\n");
	for(var i =0; i<contents.length; i++) {
	     if (isNaN(contents[i]) && hasNumbers(contents[i]) && !contents[i].includes("11-BadAudio") ) {
		alert(contents[i]);
	     }
	}
	alert("finished check");
	
    }
     
    var lastTime =currentLastTime;
    var pauseTime = 10000000.0;


     function pasteSelection() {
        audio.pause();
        currentLastTime = lastTime;
       textselection = window.getSelection().toString();
       lastTime = audio.currentTime;
       content  = document.getElementById("textId").value + "\n"+  lastSelectedText + "\n" + audio.currentTime;
       SaveFile()
       textarea = document.getElementById("textId");
       textarea.value = content ;
       textarea.scrollTop = textarea.scrollHeight;
//      audio.play();
        //alert(document.getElementById("textId").value)
     }
     function playAt() {
        pauseTime = 10000000.0;
        var playTime = document.getElementById("playtime").value;
          audio.currentTime = parseFloat(playTime);
          audio.play();
     }

     window.onselect = selectText;
     lastSelectedText  = "";
     function selectText()
      {
         lastSelectedText  = window.getSelection().toString();
         var selectedtext =document.getElementById("textId").value;
         var match =new RegExp(selectedtext, "ig");
      }

      function play() {
          audio.currentTime = getLastTransTime();
          audio.play();
       }
       function pause() {
           audio.pause();
       }
       function speedUp() {
          var speed = audio.playbackRate;
            audio.playbackRate = speed + 0.1;

       }
       function speedDown() {
           var speed = audio.playbackRate;
            audio.playbackRate = speed - 0.1;

       }


      function checkTimestampDiff() {
	   var index=1;
	   var time1 = parseFloat(filecontents[0]); 
	   var time2 = 0;
            do {

              if (!isNaN(filecontents[index]) ) {
                  time2 = parseFloat(filecontents[index]);
		  var text =  filecontents[index-1] ;  
		     if(!isNaN(time2) ) { 
 		        if( -1 == text.indexOf("11-BadAudio") ){
			    timediff = time2 -time1;
			    if(timediff < 5.0)
				alert( time1 + " & " +time2 + ": timestamp diff less than 5 seconds");
			     if(timediff >15.0)
				alert( time1 + " & " +time2 + ": timestamp diff greater than 15 seconds");
			  }
			 time1 =time2;
		      }
		 }
              index++;
          } while (index < filecontents.length	)  	
	 alert("check finished");
      }

      function getNextTimestamp() {
          var index = 0;
          var startTimeOfPlay = 0.0;
          // loop till we find timestamp greater than current timestamp
          do {

              if (!isNaN(filecontents[index]))
                  startTimeOfPlay = parseFloat(filecontents[index]);
              index++;
          } while (index < filecontents.length && (isNaN(startTimeOfPlay) || startTimeOfPlay <= audio.currentTime))
          
          //alert(startTimeOfPlay);
          return startTimeOfPlay;
      }


      function getCurrentStartTimestamp() {
          var index = 0;
          var currentStart = 0;
          var previousStart = 0;
          while (index < filecontents.length) {
              if (!isNaN(filecontents[index])) {
                  indexTime = parseFloat(filecontents[index].split()[0]);

                  if (indexTime > audio.currentTime) {
                      break;
                  }
                  previousStart = currentStart;
                  currentStart = indexTime;

              }
              index++;
          } 
          
          return currentStart;

      }

      function getPreviousTimestamp() {
          var index = 0;
          var currentStart = 0;
          var previousStart = 0;
          while (index < filecontents.length) {
              if (!isNaN(filecontents[index])) {
                  indexTime = parseFloat(filecontents[index].split()[0]);

                  if (indexTime > audio.currentTime) {
                      break;
                  }
                  previousStart = currentStart;
                  currentStart = indexTime;

              }
              index++;
          } 
          
          return previousStart;
      }


      function getPauseTimestamp() {
          var index = 0;
          var startTimeOfPlay = 0.0;
          // loop till we find timestamp greater than current timestamp
          do {

              if (!isNaN(filecontents[index]))
                  startTimeOfPlay = parseFloat(filecontents[index]);
              index++;
          } while (index < filecontents.length && (isNaN(startTimeOfPlay) || startTimeOfPlay <= audio.currentTime +0.00001))
          
          //alert(startTimeOfPlay);
          return startTimeOfPlay;
      }


      setInterval(() => SaveDoubt(), 1000);

      // Pause end of the pause time with a timer job of 1 ms
      var audio = document.getElementById("audioFileId");
      setInterval(() => pauseAtEnd(), 50);
      function pauseAtEnd() {
          if(audio.currentTime > pauseTime) {
               audio.pause();
               if (pauseTime > 0)
                   audio.currentTime = pauseTime-0.00001;
          }
      }


      function playNext() {
           audio.currentTime = getNextTimestamp() ;
           pauseTime = getPauseTimestamp() ;
           //alert("Curr:" + audio.currentTime + " Pause:" + pauseTime);
           audio.play();
       } 

      function playCurrent() {
           audio.currentTime = getCurrentStartTimestamp();
           pauseTime = getPauseTimestamp();
           //alert("Curr:" + audio.currentTime + " Pause:" + pauseTime);
           audio.play();
       }

     function playPrev() {
           audio.currentTime = getPreviousTimestamp();
           pauseTime = getPauseTimestamp();
           //alert("Curr:" + audio.currentTime + " Pause:" + pauseTime);
           audio.play();
     }


        

      </script>

  <style>
input {
    margin-left: 10x;
}
  p{
     display: inline-block;
  }
  </style>

     ';


echo  "<br>";
echo "<button type=\"button\" onclick=\"getCurrTime()\">getTime</button>";
echo "<textarea id=\"timeId\" cols=\"10\" rows=\"1\" readonly>0</textarea>";
echo "<input type=\"text\" name=\"time\" id=\"playtime\" size=\"10\" value=\"0.1\">";
echo "<button type=\"button\" onclick=\"playAt()\">Play</button>";
echo "<button type=\"button\" onclick=\"playNext()\">PlayNext(F2)</button>";
echo "<button type=\"button\" onclick=\"playCurrent()\">PlayCurrent(F4)</button>";
echo "<button type=\"button\" onclick=\"playPrev()\">PlayPrev(F7)</button>";
echo "<button type=\"button\" onclick=\"checkNumber()\">CheckNumber</button>";
echo "<button type=\"button\" onclick=\"checkTimestampDiff()\">CheckTimestampDiff</button>";

#echo  "<h4>Automatic Transcript</h4>";
#echo  "<textarea id=\"readTextId\" cols=\"100\" rows=\"4\" readonly>$read_file_content</textarea>";
echo  "</br>";
echo "<button type=\"button\" onclick=\"pause()\">Paus/Play(Esc)</button>";
echo "<button type=\"button\" onclick=\"play()\">Repeat(ctrl+y)</button>";
echo "<button type=\"button\" onclick=\"replay()\">Back2sec(Ctrl+Left)</button>";
echo "<button type=\"button\" onclick=\"forward()\">Forward2Sec(Ctrl+Right)</button>";
echo "<button type=\"button\" onclick=\"SaveFile()\">SaveFile(Ctrl+Down)</button>";

echo  "<br>";
echo  "<h4 style=\"display:inline-block;\" > Manual Transcript </h4>";
echo  "<h4 style=\"margin-left:60%;font-size:12;display:inline-block;\">Write Your Doubt or Comments</h4>";
echo  "<textarea id=\"textId\"style=\"width:75%;height:80%\"  autofocus>$file_content</textarea>";

echo  "<textarea id=\"doubt\" style=\"width:20%;height:80%;margin-left:10px;\"   >$doubt</textarea>";

echo  "</br>";


echo '
      <script>
      </script>
      ';

echo "</body>";
echo "</html>";

?>

