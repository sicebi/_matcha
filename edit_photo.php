<?php require 'header.php'; require 'required/functions.php'; iNotConnected(); ?>

<?php 
	$uploadfile = "";
	if (!empty($_POST))
	{

		if ($_FILES['my_file']['error'] > 0)
			put_flash('danger', "Error : Problem while uploading.", "edit_photo.php");

		if ($_FILES['my_file']['size'] > intval($_POST['MAX_FILE_SIZE']))
			put_flash('danger', "Error : File too big.", "edit_photo.php");

		$extensions_valides = array('jpg');

		$extension_upload = strtolower(substr(strrchr($_FILES['my_file']['name'], '.'), 1));
		if (!(in_array($extension_upload,$extensions_valides)))
			put_flash('danger', "Error : Invalid extension.", "edit_photo.php");

		require_once 'required/functions.php';
		require_once 'required/database.php';

		$uploaddir = 'img/user/' .$_SESSION['auth']->id;

		if (!is_dir($uploaddir))
			mkdir($uploaddir, 0777);

		$uploadfile = $uploaddir ."/" .basename($_FILES['my_file']['name']);

		if (file_exists($uploadfile))
			unlink($uploadfile);

		if (move_uploaded_file($_FILES['my_file']['tmp_name'], $uploadfile)) {
		    $path = $uploadfile;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			rename($uploadfile, "img/user/" .$_SESSION['auth']->id ."/profile.jpg");
			$req = $pdo->prepare('UPDATE users SET profile_img = ? WHERE id = ?');
			$req->execute(["img/user/" .$_SESSION['auth']->id ."/profile.jpg", $_SESSION['auth']->id]);
			$_SESSION['auth']->profile_img = "img/user/" .$_SESSION['auth']->id ."/profile.jpg";
		} else {
			put_flash('danger', "Error : Problem while uploading.", "edit_photo.php");
		}

	}
?>


<div class="banner-home">
	<div class="login">
		<div style="position: relative; top: 15%; color: whitesmoke; z-index: 2;">
			<?php if(empty($_POST)) { ?>
					<center><h1>No file selected</h1></center>
						<center>
						<form class="form-group" method="POST" action="edit_photo.php" enctype="multipart/form-data">
						     <label for="icone">File (JPG | 5 Mo) :</label> 
						     <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
						     <input type="file" name="my_file" id="my_file" /><br><br>
						     <input class="btn-commenta" type="submit" name="submit" value="Upload" />
						</form><br>
						</center>
				<?php }else{ ?>
					<br><br>
					<center>
						<img src="<?php echo "img/user/" .$_SESSION['auth']->id ."/profile.jpg"; ?>" width="20%">
						<br><br>
						<a href="edit_photo.php"><input type="submit" class="btn btn-primary" value="Change"></a>
						<a href="profile.php"><input type="submit" class="btn btn-primary" value="Save"></a>
					</center>
				<?php } ?>
		</div>
	</div>
</div>