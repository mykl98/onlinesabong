<!DOCTYPE html>
<html>
  <head>
    <title>Title of the document</title>
    <style>
      body {
        background-color: #ffffff;
      }
      div {
        width: 50%;
      }
    </style>
  </head>
  <body>
    <iframe src="http://192.168.1.10/cgi-bin/mjpg/video.cgi?channel=1&subtype=1" id="target"></iframe>
    <script>
      var div = document.getElementById("target");
      div.onload = function() {
        div.style.height = div.contentWindow.document.body.scrollHeight + 'px';
      }
    </script>
  </body>
</html>