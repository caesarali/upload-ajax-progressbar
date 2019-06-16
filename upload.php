<?php
	define("UPLOAD_DIR", "files/");

	if (!empty($_FILES["file"])) {
		$file = $_FILES["file"];

		if ($file["error"] !== UPLOAD_ERR_OK) {
			echo 'error';
			exit;
		}

		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
		
		$i = 0;
		$parts = pathinfo($name);
		while (file_exists(UPLOAD_DIR . $name)) {
			$i++;
			$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
		}

		$success = move_uploaded_file($file["tmp_name"], UPLOAD_DIR . $name);
		if ($success) { 
			echo 'success';
			exit;
		}
	}
?>