<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/handleForms.php'; ?>
<?php require_once 'core/models.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT USER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .main-body{
        padding: 2em;
        margin: auto;
        width: 50%;
      }
    </style>
</head>
<body>
<?php $getUserByID = getUserByID($pdo, $_GET['user_id']); ?>
    <div class="main-body">
        <a href="viewprojects.php?user_id=<?php echo $_GET['user_id']; ?>"> Return to View All Projects</a>

        <h1> UPDATE PROJECT DETAILS</h1>

        <div class="mb-5">
        <?php $getProjectById = getProjectById($pdo, $_GET['project_id']); 
        ?>
            <form action="core/handleForms.php?project_id=<?php echo $_GET['project_id']; ?>
	&user_id=<?php echo $_GET['user_id']; ?>" method="POST">
                <div class="mb-2">
                    <label for="project_name" class="form-label">Project Name: </label>
                    <input type="text" class="form-control" name="project_name" value="<?php echo $getProjectById[0]['project_name'] ?>" required>
                </div>
                <div class="mb-2">
                    <label for="technologies_used" class="form-label">Technologies used: </label>
                    <input type="text" class="form-control" name="technologies_used" value="<?php echo $getProjectById[0]['technologies_used'] ?>" required> 
                </div>
                <div class="mb-2">
                    <label for="date_started" class="form-label">Date Started: </label>
                    <input type="date" class="form-control" name="date_started" value="<?php echo $getProjectById[0]['date_started'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="date_finished" class="form-label">Date Finished: </label>
                    <input type="date" class="form-control" name="date_finished" value="<?php echo $getProjectById[0]['date_finished'] ?>">
                </div>
            <button type="submit" class="btn btn-primary" name="editProjectBtn">Submit</button>
        </form>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
