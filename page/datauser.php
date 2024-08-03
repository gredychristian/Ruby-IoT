  <?php

  if($_SESSION['role'] != "Admin"){
    echo "<script> location.href='index.php' </script>";
  }

  $page = $_GET['page'];
  $insert = false;

  if (isset($_POST['edit_data'])) {
    $old_id = $_POST['edit_data'];

    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];
    $active = $_POST['active'];

    if($_POST['password'] == ""){
      $sql_edit = "UPDATE user SET username = '$username', fullname = '$fullname', role = '$role', active = '$active' WHERE username = '$old_id'";
    } else {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $sql_edit = "UPDATE user SET username = '$username', password = '$password', fullname = '$fullname', role = '$role', active = '$active' WHERE username = '$old_id'";
    }

    mysqli_query($conn, $sql_edit);

  } else if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql_insert = "INSERT INTO user (username, password, fullname, role) VALUES ('$username', '$password', '$fullname', '$role')";
    mysqli_query($conn, $sql_insert);
    $insert = true;
  }

  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql_select_data = "SELECT * FROM user WHERE username = '$id' LIMIT 1";

    $result = mysqli_query($conn, $sql_select_data);
    $data = mysqli_fetch_assoc($result);
  }

  $sql = "SELECT * FROM user";
  $result = mysqli_query($conn, $sql);
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Accounts</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">

      <div class="container-fluid">
        <!-- Alert Success -->
        <?php
        if ($insert == true) {
          alertsuccess("New officer account has been registered successfully.");
        }

        ?>

        <div class="row">
          <div class="col-lg-12">

            <!-- List of Account -->
            <div class="card">
              <div class="card-header">

                <h3 class="card-title">Data Monitoring Officer</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <!-- Table Header -->
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th>Full Name</th>
                      <th>Role</th>
                      <th>Active Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <!-- Fetch Data from Database and Show into List Tables -->
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                      <tr>
                        <td><?php echo $row['username'] ?></td>
                        <td><?php echo $row['fullname'] ?></td>
                        <td><?php echo $row['role'] ?></td>
                        <td><?php echo $row['active'] ?></td>
                        <td><a href="?page=<?php echo $page ?>&edit=<?php echo $row['username'] ?>"><i class="fas fa-edit"></i></td>
                      </tr>
                    <?php } ?>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>

            <?php if (!isset($_GET['edit'])) { ?>

              <!-- Add New Account -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add New Account</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="?page=datauser">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" name="username" placeholder="Each account must have a different username" required>
                    </div>
                    <div class="form-group">
                      <label>Full Name</label>
                      <input type="text" class="form-control" name="fullname" required>
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" required>
                        <div class="input-group-append">
                          <span class="input-group-text" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Role</label>
                      <select class="form-control" name="role">
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Account Status</label>
                      <select class="form-control" name="active">
                        <option value="Yes">Active</option>
                        <option value="No">Inactive</option>
                      </select>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>

            <?php } else { ?>

              <!-- Edit Account -->
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Modify Account Data</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="?page=datauser">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="hidden" name="edit_data" value="<?php echo $data['username'] ?>">
                      <input type="text" class="form-control" name="username" value="<?php echo $data['username'] ?>" placeholder="Each account must have a different username" required>
                    </div>
                    <div class="form-group">
                      <label>Full Name</label>
                      <input type="text" class="form-control" name="fullname" value="<?php echo $data['fullname'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="text" class="form-control" name="password" placeholder="Leave this blank if you don't want to change password">
                    </div>
                    <div class="form-group">
                      <label>Role</label>
                      <select class="form-control" name="role" value="<?php echo $data['role'] ?>" required>
                        <?php if ($data['role'] == "Admin") { ?>
                          <option value="Admin">Admin</option>
                          <option value="User">User</option>
                        <?php } else { ?>
                          <option value="User">User</option>
                          <option value="Admin">Admin</option>
                        <?php } ?>

                      </select>
                    </div>
                    <div class="form-group">
                      <label>Account Status</label>
                      <select class="form-control" name="active" value="<?php echo $data['active'] ?>" required>
                        <?php if ($data['active'] == "Yes") { ?>
                          <option value="Yes">Active</option>
                          <option value="No">Inactive</option>
                        <?php } else { ?>
                          <option value="No">Inactive</option>
                          <option value="Yes">Active</option>
                        <?php } ?>

                      </select>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            <?php } ?>
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      var eyeIcon = document.getElementById("eyeIcon");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      }
    }
  </script>