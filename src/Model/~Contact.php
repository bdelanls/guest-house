<?php
//Base de donnée
if(!empty($_POST["send"])) {
	$name = $_POST["name"];
	$email = $_POST["email"];
	$subject = $_POST["subject"];
	$message = $_POST["message"];

	$connexion = mysqli_connect("localhost", "root", "", "contact_form") or die("Erreur de connexion: " . mysqli_error($connexion));
	$result = mysqli_query($connexion, "INSERT INTO contact (name, email, subject, message) VALUES ('" . $name. "', '" . $email. "','" . $subject. "','" . $message. "')");
	if($result){
		$db_msg = "Vos informations de contact sont enregistrées avec succés.";
		$type_db_msg = "success";
	}else{
		$db_msg = "Erreur lors de la tentative d'enregistrement de contact.";
		$type_db_msg = "error";
	}
	
}
//envoie du mail
if(!empty($_POST["send"])) {
	$name = $_POST["name"];
	$email = $_POST["email"];
	$subject = $_POST["subject"];
	$message = $_POST["message"];

	$toEmail = "VotreAdresse@gmail.com";
	$mailHeaders = "From: " . $name . "<". $email .">\r\n";
	if(mail($toEmail, $subject, $message, $mailHeaders)) {
	    $mail_msg = "Vos informations de contact ont été reçues avec succés.";
		$type_mail_msg = "success";
	}else{
		$mail_msg = "Erreur lors de l'envoi de l'e-mail.";
		$type_mail_msg = "error";
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<main>
			<div class="container shadow-lg p-3 mt-5 mb-5 bg-white rounded col-md-6">
				<form action="contact.php" method="POST">
					<header class="bg-dark text-light p-3 ">Contact</header>
					<hr>
					
					<div class="col-md-12">
						<label for="name" class="form-label">Votre nom*</label> <br> 
						<input class="form-control" type="text" name="pseudo" id="pseudo1"  placeholder="Nom complet" required><br>
					</div>
					<div class="col-md-12">
						<label for="name" class="form-label">Votre prénom*</label> <br> 
						<input class="form-control" type="text" name="pseudo" id="pseudo2"  placeholder="Prénom complet"><br>
					</div>
					<div class="col-md-12">
						<label for="email" class="form-label">Votre e-mail*</label> <br> 
						<input class="form-control" type="mail" name="mail" id="mail" placeholder="adresse@exemple.com" required><br>
					</div>
					<div class="col-md-12">
						<label for="sujet" class="form-label">Sujet</label><br/>
							<select name="sujet" id="sujet" class="form-select" >  
									<option value selected="selected">Choix...</option>  
									<option value="text">Sujestion</option>
									<option value="error">Reporter erreur</option>
									<option value="others">Autres</option>
							</select><br>
					</div>
					<div class="col-md-12">
						<label class="form-label" for="precisions">Message :</label><br />
						<textarea class="form-control"  name="precisions" id="precisions"></textarea>    
					</div><br>
					
					<div class="col-md-12 ">
						<input type="submit" value="Envoyer" class="btn btn-primary"/>	
					</div>
					<div id="statusMessage"> 
					<?php if (! empty($db_msg)) { ?>
					<p class='<?php echo $type_db_msg; ?>Message'><?php echo $db_msg; ?></p>
					<?php } ?>
					<?php if (! empty($mail_msg)) { ?>
					<p class='<?php echo $type_mail_msg; ?>Message'><?php echo $mail_msg; ?></p>
					<?php } ?>
					</div>

				</form>
				
            </div>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>
