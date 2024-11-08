<?php  
require_once 'models.php';
require_once 'dbConfig.php';

if (isset($_POST['registerUserBtn'])) {
	// Collect additional form inputs
	$username = $_POST['username'];
	$password = sha1($_POST['password']);
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$dateofbirth = $_POST['dateofbirth'];
	$specialization = $_POST['specialization'];

	// Ensure all fields are filled out
	if (!empty($username) && !empty($password) && !empty($firstname) && !empty($lastname) && !empty($dateofbirth) && !empty($specialization)) {

		// Pass all fields to insertNewUser function
		$insertQuery = insertNewUser($pdo, $username, $password, $firstname, $lastname, $dateofbirth, $specialization);

		if ($insertQuery) {
			header("Location: ../login.php");
		} else {
			$_SESSION['message'] = "Registration failed. Please try again.";
			header("Location: ../register.php");
		}
	} else {
		$_SESSION['message'] = "Please make sure all input fields are filled for registration!";
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	if (!empty($username) && !empty($password)) {
		$loginQuery = loginUser($pdo, $username, $password);
	
		if ($loginQuery) {
			header("Location: ../index.php");
		} else {
			$_SESSION['message'] = "Login failed. Please check your credentials.";
			header("Location: ../login.php");
		}
	} else {
		$_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
		header("Location: ../login.php");
	}
}

if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	header('Location: ../login.php');
}

if (isset($_POST['editUserBtn'])) {
	$query = updateWebDev($pdo, $_POST['firstName'], $_POST['lastName'], 
		$_POST['dateOfBirth'], $_POST['specialization'], $_GET['user_id']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Edit failed";;
	}
}

if (isset($_POST['deleteUserBtn'])) {
	$query = deleteUser($pdo, $_GET['user_id']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Deletion failed";
	}
}

//project
if (isset($_POST['addNewProjectBtn'])) {
	$query = addProject($pdo, $_POST['project_name'], $_POST['technologies_used'], $_POST['date_started'], $_POST['date_finished'], $_GET['user_id']);

	if ($query) {
		header("Location: ../viewprojects.php?user_id=" .$_GET['user_id']);
	}
	else {
		echo "Insertion failed";
	}
}

if (isset($_POST['editProjectBtn'])) {
	$query = updateProject($pdo, $_POST['project_name'], $_POST['technologies_used'], $_POST['date_started'], $_POST['date_finished'], $_GET['project_id']);

	if ($query) {
		header("Location: ../viewprojects.php?user_id=" .$_GET['user_id']);
	}
	else {
		echo "Update failed";
	}
}

if (isset($_POST['deleteProjectBtn'])) {
	$query = deleteProject($pdo, $_GET['project_id']);

	if ($query) {
		header("Location: ../viewprojects.php?user_id=" .$_GET['user_id']);
	}
	else {
		echo "Deletion failed";
	}
}
?>