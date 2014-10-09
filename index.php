<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
	  <link rel="stylesheet" href="foundation/css/normalize.css">
	  <link rel="stylesheet" href="foundation/css/foundation.css">

	<script src="foundation/js/vendor/modernizr.js"></script>
	<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
</head>



<body>
<script>
tinymce.init({
    selector: "textarea#elm1",
    language : 'es_MX',
    theme: "modern",
    width: 600,
    height: 300,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],
   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
   style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
 }); 
</script>

<script language="javascript">
	fields = 0;
	function addInput() {
		if (fields != 10) {
			document.getElementById('text').innerHTML += "<div class='large-6 columns'><label>Nombre<input type='text' name='name["+fields+"]' /></label></div><div class='large-6 columns'><label>Correo:<input type='text' name='email["+fields+"]' /></label></div><br />";
			fields += 1;
		} else {
			document.getElementById('text').innerHTML += "<br />Only 10 upload fields allowed.";
			document.form.add.disabled=true;
		}
	}
</script>

<div style="padding: 60px; margin: auto">
	<h1>Mail</h1>
	<form method="post">
		<div class="row">
			<div class="large-6 columns">
				<label>
					Mandrill User Name:
					<input type="text" name="username" />
				</label>
			</div>
			<div class="large-6 columns">
				<label>
					Mandrill API Key:
					<input type="text" name="apikey" />
				</label>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="large-12 columns">
				<label>
					Subject:
					<input type="text" name="subject">
				</label>
			</div>
    		<div class="large-6 columns">
				<input class="button" type="button" onclick="addInput()" name="add" value="Add Mail" />
				<div id="text"></div>
			</div>
    		<div class="large-6 columns">	
    			<textarea id="elm1" name="area"></textarea>
    			<input class="button" type="submit" value="Enviar" >
    		</div>
		</div>
	</form>
</div>
	<pre>
		<?php 
			if(isset($_POST['area'])){
				foreach ($_POST['email'] as $email) {
					echo $email;
				}
			}

		?>

	</pre>

	<script src="foundation/js/vendor/jquery.js"></script>
  	<script src="foundation/js/foundation.min.js"></script>
  	<script>
    	$(document).foundation();
  	</script>
</body>


<?php

require_once 'vendor/autoload.php';

if(isset($_POST['area'])){
	$username=$_POST['username'];
	$apikey=$_POST['apikey'];
	$subject=$_POST['subject'];

	$transport = \Swift_SmtpTransport::newInstance(
		'smtp.mandrillapp.com',
		587
	)->setUserName($username)
	 ->setPassword($apikey);

	$swift = \Swift_Mailer::newInstance($transport);
	//foreach ($to as $email => $name) {
	foreach ($_POST['email'] as $email) {
	$content = $_POST['area'];	
		$message = \Swift_Message::newInstance($subject)
					->setFrom(["kerberos.it.s@gmail.com" => "Alecz Localhost"])
					->setTo(array($email))
					->setBody($content, 'text/html')
					->addPart(strip_tags($content), 'text/plain');

		$result = $swift->send($message);
		
	}
}