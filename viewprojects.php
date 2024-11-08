<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROJECTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      .main-body{
        padding: 2em;
        margin: auto;
        width: 75%;
      }
    </style>
</head>
<body>
    <div class="main-body">
    <a href="index.php">Return to home</a>
    <?php 
        // Check user_id is set
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            $getUserByID = getUserByID($pdo, $user_id);
        } else {
            die("Error: User ID not provided.");
        }
    ?>
        <br>
        <div class="card mb-5" style="width: 100%;">
            <div class="card-body">
                <h2 class="card-subtitle"> Portfolio Owner: <span class="badge text-bg-secondary"> <?php echo $getUserByID['first_name'] . ' ' . $getUserByID['last_name'] ?> </span></h2>
            </div>
        </div>
        <h3> Add a project </h3>
        <div class="mb-5">
            <form action="core/handleForms.php?user_id=<?php echo $user_id; ?>" method="post">
                <div class="mb-2">
                    <label for="project_name" class="form-label">Project Name: </label>
                    <input type="text" class="form-control" name="project_name" required>
                </div>
                <div class="mb-2">
                    <label for="technologies_used" class="form-label">Technologies used: </label>
                    <input type="text" class="form-control" name="technologies_used" required> 
                </div>
                <div class="mb-2">
                    <label for="date_started" class="form-label">Date Started: </label>
                    <input type="date" class="form-control" name="date_started" required>
                </div>
                <div class="mb-3">
                    <label for="date_finished" class="form-label">Date Finished: </label>
                    <input type="date" class="form-control" name="date_finished">
                </div>
            <button type="submit" class="btn btn-primary" name="addNewProjectBtn">Submit</button>
        </form>
        </div>

        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Project ID</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Technologies</th>
                        <th scope="col">Date Started</th>
                        <th scope="col">Date Finished</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $getProjectsByUser = getProjectsByUser($pdo, $user_id); ?>
                <?php foreach ($getProjectsByUser as $row) { ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($row['project_id']); ?></th>
                        <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['technologies_used']); ?></td>
                        <td><?php echo htmlspecialchars($row['formatted_start']); ?></td>
                        <td><?php echo htmlspecialchars($row['formatted_finish']); ?></td>
                        <td>
                            <a href="editproject.php?project_id=<?php echo $row['project_id']; ?>&user_id=<?php echo $user_id; ?>"  class="btn btn-secondary">Edit</a>
                            <a href="deleteproject.php?project_id=<?php echo $row['project_id']; ?>&user_id=<?php echo $user_id; ?>" class="btn btn-danger">Delete</a>
                        </td> 
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>