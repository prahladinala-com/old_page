<!DOCTYPE html>
<html>
<head>
 <title>Qr code</title>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<textarea onkeyup="generate_qrcode(this.value)" cols="50" rows="5"></textarea>
<div id="result"></div>
</body>
<script>
 function generate_qrcode(sample){
 $.ajax({
 type: 'post',
 url: 'generator.php',
 data : {sample:sample},
 success: function(code){
 $('#result').html(code);
 }
 });
 }
</script>
</html>