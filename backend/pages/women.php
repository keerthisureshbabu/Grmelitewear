<?php
include("db.php");
include("header.php");

// Handle Add/Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $conn->query("UPDATE women SET name='$name' WHERE id=$id");
    } else {
        // Add
        $conn->query("INSERT INTO women (name) VALUES ('$name')");
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM women WHERE id=$id");
}

// Handle Edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM women WHERE id=$id");
    $edit = $result->fetch_assoc();
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
    <h2 class="my-3">Women's Category</h2>
        <form method="post" class="row g-3 mb-4">
            <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
            <div class="col-auto">
                <input type="text" name="name" class="form-control" placeholder="Name"
                       value="<?php echo $edit['name'] ?? ''; ?>" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit ? 'Update' : 'Add'; ?>
                </button>
            </div>
        </form>


        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM women");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>
                            <a href='?edit={$row['id']}' class='btn btn-sm btn-warning'>
                                <i class='ri-edit-2-line'></i>
                            </a>
                            <a href='?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete?\")'>
                                <i class='ri-delete-bin-6-line'></i>
                            </a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include("footer.php");
?>
