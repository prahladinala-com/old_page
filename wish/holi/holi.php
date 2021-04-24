<!DOCTYPE HTML>
<html lang="en_US">
<head>
	<meta charset="UTF-8">
	<title>Happy Holi</title>
 <style type="text/css">
 #fromtext{
	 position:absolute;
	 top: 20%;
	 left: 30%;
	 bottom: 80%;
	 right: 30%;
	 color:#fff;
	 animation:anim 5s infinite;
}
@keyframes anim{
	0%{color:#F00;}
	25%{color:#FF0; transform:scale(1.5);}
	50%{color:#FF0080;transform:scale(0.8);}
	75%{color:#8000FF;transform:scale(1.5);}
	100%{color:#F00;transform:scale(1);}
}
.yourname {
  background-color : #31B0D5;
  color: white;
  padding: 20px 40px;
  border-radius: 4px;
  border-color: #46b8da;
  font-size: 40px;
  display: inline-block;
  text-decoration: none;
}
.share {
  background-color : #25d366;
  color: white;
  padding: 20px 40px;
  border-radius: 4px;
  border-color: #128c7e;
  font-size: 40px;
  display: inline-block;
  text-decoration: none;
}
#mybutton {
  position: fixed;
  bottom: 4px;
  left: 10px;
}
#mybutton2 {
  position: fixed;
  bottom: 4px;
  left: 250px;
}
.about {
    background-color: #C1AFE3;
    background-repeat: no-repeat;
    width: auto;
    height: 100%;
    background-size: 100% 100%;
    background-attachment: scroll;
}
 </style>
</head>
<body class="about" background="images/holi.png">
	
    <h1 id="fromtext">
    <?php
$str =  $_GET['name'];
$str = strtoupper($str);
echo $str;
?>
  Wishes you a</h1>
    <canvas id="canvas"></canvas>
 <script type="text/javascript" src="js/crackers.js"></script>
<div id="mybutton">
<a href="http://prtheking.000webhostapp.com/wish/friendship/" class="yourname">YOUR <br /> NAME</a>
</div>
<div id="mybutton2">
<a href="https://wa.me/+917396170742?text=I%20checked%20your%20website%20just%20now.😊😇😌%20%20%20%20%20%20http://prtheking.000webhostapp.com/ %20%20%20%20%20%20%20%20%20 It is%20really%20awesome 👌😍😘%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20I%20loved%20it%20%20%20%20❤❤❤" class="share">MAKE DEVELOPER <br /> HAPPY :)</a>
</div>
</body>
</html>