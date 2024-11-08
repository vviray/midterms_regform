<?php  
require_once 'dbConfig.php';

function insertNewUser($pdo, $username, $password, $firstname, $lastname, $dateofbirth, $specialization) {
    // Check if username already exists
    $checkUserSql = "SELECT 
		users.user_id,
		users.username,
		users.password,
		users.first_name,
		users.last_name,
		users.date_of_birth,
		users.date_account_created,
		web_devs.specialization,
		web_devs.hire_date
	FROM 
		users
	JOIN 
		web_devs ON users.user_id = web_devs.user_id WHERE username = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$username]);

    if ($checkUserSqlStmt->rowCount() == 0) {
        // Call the stored procedure to insert a new user and web developer
        $sql = "CALL add_user_with_web_dev(?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Execute the stored procedure with the provided parameters
        $executeQuery = $stmt->execute([
            $username,
            $password,
            $firstname,
            $lastname,
            $dateofbirth,
            $specialization
        ]);

        if ($executeQuery) {
            $_SESSION['message'] = "User and web developer successfully inserted";
            return true;
        } else {
            $_SESSION['message'] = "An error occurred in the query";
            return false;
        }
    } else {
        $_SESSION['message'] = "User already exists";
        return false;
    }
}


function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		} else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	} else {
		$_SESSION['message'] = "Username doesn't exist in the database. You may consider registration first";
	}
}

function getAllUsers($pdo) {
	$sql = "SELECT 
		users.user_id,
		users.username,
		users.password,
		users.first_name,
		users.last_name,
		users.date_of_birth,
		DATE_FORMAT(users.date_of_birth, '%M %d, %Y') AS formatted_bday,
		users.date_account_created,
		web_devs.specialization,
		web_devs.hire_date,
		DATE_FORMAT(web_devs.hire_date, '%M %d, %Y') AS formatted_hire
	FROM 
		users
	JOIN 
		web_devs ON users.user_id = web_devs.user_id;
	";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT 
		users.user_id,
		users.username,
		users.password,
		users.first_name,
		users.last_name,
		users.date_of_birth,
		DATE_FORMAT(users.date_of_birth, '%M %d, %Y') AS formatted_bday,
		users.date_account_created,
		web_devs.specialization,
		web_devs.hire_date,
		DATE_FORMAT(web_devs.hire_date, '%M %d, %Y') AS formatted_hire
	FROM 
		users
	JOIN 
		web_devs ON users.user_id = web_devs.user_id
	WHERE 
		users.user_id = ?;
	";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function updateUser($pdo, $first_name, $last_name, 
	$date_of_birth, $specialization, $web_dev_id) {

	$sql = "UPDATE users 
		JOIN web_devs ON users.user_id = web_devs.user_id
		SET users.first_name = ?,
			users.last_name = ?,
			users.date_of_birth = ?,
			web_devs.specialization = ?
		WHERE web_devs.web_dev_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$first_name, $last_name, 
	$date_of_birth, $specialization, $web_dev_id]);
	
	if ($executeQuery) {
		return true;
	}

}

function deleteUser($pdo, $user_id) {
	$deleteUser = "DELETE FROM users WHERE user_id = ?";
	$deleteStmt = $pdo->prepare($deleteUser);
	$executeDeleteQuery = $deleteStmt->execute([$user_id]);

	if ($executeDeleteQuery) {
			return true;
	}
}


function viewAllUserInfo($pdo, $user_id){
	$sql = "
        SELECT 
            u.user_id,
            u.username,
            u.first_name,
            u.last_name,
            u.date_of_birth,
            u.date_account_created,
            wd.specialization,
            wd.hire_date,
            p.project_id,
            p.project_title,
            p.technologies_used,
            p.date_started,
            p.date_finished,
			DATE_FORMAT(u.date_of_birth, '%M %d, %Y') AS formatted_bday,
			DATE_FORMAT(wd.hire_date, '%M %d, %Y') AS formatted_hire
        FROM users u
        LEFT JOIN web_devs wd ON u.user_id = wd.user_id
        LEFT JOIN projects p ON wd.web_dev_id = p.web_dev_id
        ORDER BY u.user_id;
    ";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$web_dev_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function getProjectsByUser($pdo, $user_id){
	$sql = "
        SELECT 
            p.project_id,
            p.project_name,
            p.technologies_used,
            p.date_started,
            p.date_finished,
			DATE_FORMAT(p.date_started, '%M %d, %Y') AS formatted_start,
    		COALESCE(DATE_FORMAT(p.date_finished, '%M %d, %Y'), 'TBA') AS formatted_finish
        FROM projects p
        INNER JOIN web_devs wd ON p.web_dev_id = wd.web_dev_id
        INNER JOIN users u ON wd.user_id = u.user_id
        WHERE u.user_id = :user_id
        ORDER BY p.date_started DESC;
    ";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([':user_id' => $user_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getProjectById($pdo, $project_id){
	$sql = "SELECT 
		p.project_id,
		p.project_name,
		p.technologies_used,
		p.date_started,
		p.date_finished,
		DATE_FORMAT(p.date_started, '%M %d, %Y') AS formatted_start,
    	COALESCE(DATE_FORMAT(p.date_finished, '%M %d, %Y'), 'TBA') AS formatted_finish,
		wd.web_dev_id,
		wd.specialization,
		u.user_id,
		u.username,
		u.first_name,
		u.last_name
	FROM projects p
	INNER JOIN web_devs wd ON p.web_dev_id = wd.web_dev_id
	INNER JOIN users u ON wd.user_id = u.user_id
	WHERE p.project_id = :project_id;
	";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([':project_id' => $project_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function addProject($pdo, $project_name, $technologies_used, $date_started, $date_finished, $web_dev_id){
	if (empty($date_finished)) {
        $date_finished = null; 
    }
	$sql = "INSERT INTO projects (project_name, technologies_used, date_started, date_finished, web_dev_id) VALUES (?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$project_name, $technologies_used, $date_started, $date_finished, $web_dev_id]);
	if ($executeQuery) {
		return true;
	}
}

function updateProject($pdo, $project_name, $technologies_used, $date_started, $date_finished, $project_id){
	if(empty($date_finished)){
		$date_finished = null;
	}

	$sql = "UPDATE projects
			SET project_name = ?,
				technologies_used = ?,
				date_started = ?,
				date_finished = ?
			WHERE project_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$project_name, $technologies_used, $date_started, $date_finished, $project_id]);

	if ($executeQuery) {
		return true;
	}
}

function deleteProject ($pdo, $project_id){
	$sql = "DELETE FROM projects WHERE project_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$project_id]);
	if ($executeQuery) {
		return true;
	}
}

?>