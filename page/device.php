  <?php
  $page = $_GET['page'];
  $insert = false;

  if (isset($_POST['edit_data'])) {
    $old_id = $_POST['edit_data'];

    $serial_number = $_POST['serial_number'];
    $mcu_type = $_POST['mcu_type'];
    $location = $_POST['location'];
    $active = $_POST['active'];

    $sql_edit = "UPDATE devices SET serial_number = '$serial_number', mcu_type = '$mcu_type', location = '$location', active = '$active' WHERE serial_number = '$old_id'";
    mysqli_query($conn, $sql_edit);

  } else if (isset($_POST['serial_number'])) {
    $serial_number = $_POST['serial_number'];
    $mcu_type = $_POST['mcu_type'];
    $location = $_POST['location'];

    $sql_insert = "INSERT INTO devices (serial_number, mcu_type, location) VALUES ('$serial_number', '$mcu_type', '$location')";
    mysqli_query($conn, $sql_insert);
    $insert = true;
  }

  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql_select_data = "SELECT * FROM devices WHERE serial_number = '$id' LIMIT 1";

    $result = mysqli_query($conn, $sql_select_data);
    $data = mysqli_fetch_assoc($result);
  }

  $sql = "SELECT * FROM devices";
  $result = mysqli_query($conn, $sql);
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Devices</h1>
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
          alertsuccess("New device has been added and registered successfully.");
        }

        ?>

        <div class="row">
          <div class="col-lg-12">

            <!-- List of Devices -->
            <div class="card">
              <div class="card-header">


                <h3 class="card-title">Registered IoT Devices</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <!-- Table Header -->
                  <thead>
                    <tr>
                      <th>Serial Number</th>
                      <th>MCU Type</th>
                      <th>Location</th>
                      <th>Date Registered</th>
                      <th>Active Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <!-- Fetch Data from Database and Show into List Tables -->
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                      <tr>
                        <td><?php echo $row['serial_number'] ?></td>
                        <td><?php echo $row['mcu_type'] ?></td>
                        <td><?php echo $row['location'] ?></td>
                        <td><?php echo $row['time_created'] ?></td>
                        <td><?php echo $row['active'] ?></td>
                        <td><a href="?page=<?php echo $page ?>&edit=<?php echo $row['serial_number'] ?>"><i class="fas fa-edit"></i></td>
                      </tr>
                    <?php } ?>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>

            <?php if (!isset($_GET['edit'])) { ?>

              <!-- Add New Device -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Register New Device</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="?page=device">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Serial Number</label>
                      <input type="text" class="form-control" name="serial_number" placeholder="Each device must have a different serial number" maxlength="10" required>
                    </div>
                    <div class="form-group">
                      <label>MCU Type</label>
                      <input type="text" class="form-control" name="mcu_type" required>
                    </div>
                    <div class="form-group">
                      <label>Location</label>
                      <input type="text" class="form-control" name="location">
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>

            <?php } else { ?>

              <!-- Edit Device -->
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Edit Device</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="?page=device">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Serial Number</label>
                      <input type="hidden" name="edit_data" value="<?php echo $data['serial_number'] ?>">
                      <input type="text" class="form-control" name="serial_number" value="<?php echo $data['serial_number'] ?>" placeholder="Each device must have a different serial number" maxlength="10" required>
                    </div>
                    <div class="form-group">
                      <label>MCU Type</label>
                      <input type="text" class="form-control" name="mcu_type" value="<?php echo $data['mcu_type'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Location</label>
                      <input type="text" class="form-control" name="location" value="<?php echo $data['location'] ?>">
                    </div>
                    <div class="form-group">
                      <label>MCU Status</label>
                      <select class="form-control" name="active" value="<?php echo $data['active'] ?>" required>
                        <?php if ($data['active'] == "Yes") { ?>
                          <option value="Yes">Online</option>
                          <option value="No">Offline</option>
                        <?php } else { ?>
                          <option value="No">Offline</option>
                          <option value="Yes">Online</option>
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