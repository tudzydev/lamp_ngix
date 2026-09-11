<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch Data from 'titanic' Table
$sql = "SELECT * FROM titanic";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanic Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Titanic Passenger Data</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Index</th>
                        <th>Passenger ID</th>
                        <th>Survived</th>
                        <th>Pclass</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>SibSp</th>
                        <th>Parch</th>
                        <th>Ticket</th>
                        <th>Fare</th>
                        <th>Cabin</th>
                        <th>Embarked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['index']; ?>
                            </td>
                            <td><?php echo $row['PassengerId']; ?>
                            </td>
                            <td><?php echo $row['Survived']; ?>
                            </td>
                            <td><?php echo $row['Pclass']; ?>
                            </td>
                            <td><?php echo $row['Name']; ?>
                            </td>
                            <td><?php echo $row['Sex']; ?>
                            </td>
                            <td>
                                <?php echo $row['Age']; ?>
                            </td>
                            <td>
                                <?php echo $row['SibSp']; ?>
                            </td>
                            <td>
                                <?php echo $row['Parch']; ?>
                            </td>
                            <td>
                                <?php echo $row['Ticket']; ?>
                            </td>
                            <td>
                                <?php echo $row['Fare']; ?>
                            </td>
                            <td>
                                <?php echo $row['Cabin']; ?>
                            </td>
                            <td>
                                <?php echo $row['Embarked']; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No records found in the Titanic table.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>