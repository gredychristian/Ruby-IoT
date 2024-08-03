  <?php
  $sql = "SELECT * FROM devices WHERE active = 'Yes'";
  $result = mysqli_query($conn, $sql);
  ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Monitoring Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

        <!-- Card Live Data -->
        <div class="row">

          <!-- Temperature -->
          <div class="col-lg-4">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><span id="temp">N/A</span> °C</h3>

                <p>Temperature</p>
              </div>
              <div class="icon">
                <i class="fas fa-thermometer-half"></i>
              </div>
            </div>
          </div>

          <!-- Humidity -->
          <div class="col-lg-4">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><span id="humi">N/A</span> %</h3>

                <p>Humidity</p>
              </div>
              <div class="icon">
                <i class="fas fa-tint"></i>
              </div>
            </div>
          </div>

          <!-- Potentiometer -->
          <div class="col-lg-4">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><span id="pot">N/A</span> %</h3>

                <p>Potentiometer</p>
              </div>
              <div class="icon">
                <i class="fas fa-tachometer-alt"></i>
              </div>
            </div>
          </div>

        </div>

        <!-- Card Slider -->
        <div class="row">
          <div class="col-lg-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Servo</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row margin">
                  <div class="col-12">
                    <input id="srv" type="text" onchange="publishSrv(this)" value="">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-lg-4">
            <!-- Radio Buttons -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">LED Light</h3>
              </div>
              <div class="card-body table-responsive pad">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn btn-primary" id="label-led1on">
                    <input type="radio" name="led1" id="led1on" onchange="publishLed(this)" autocomplete="off"> ON
                  </label>
                  <label class="btn btn-primary" id="label-led1off">
                    <input type="radio" name="led1" id="led1off" onchange="publishLed(this)" autocomplete="off"> OFF
                  </label>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.col -->
        </div>

        <!-- Card Hover Table -->
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">List of Devices</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Serial Number</th>
                      <th>MCU Type</th>
                      <th>Location</th>
                      <th>Device Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                      <td><?php echo $row['serial_number'] ?></td>
                      <td><?php echo $row['mcu_type'] ?></td>
                      <td><?php echo $row['location'] ?></td>
                      <td style="color:gray;" id="robotics.upnjatim/status/<?php echo $row['serial_number'] ?>">UNKNOWN</td>
                    </tr>
                    <?php } ?>

                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
      <!-- /.col-md-6 -->
    </div>

  </div>
  <!-- /.content-wrapper -->

  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

  <script>
    const clientId = Math.random().toString(16).substr(2, 8);
    const host = 'wss://roboticsupnjatim.cloud.shiftr.io:443';

    const options = {
      keepalive: 30,
      clientId: clientId,
      username: "roboticsupnjatim",
      password: "pGPF02Nk6Fr8Tr2480238",
      protocolId: 'MQTT',
      protocolVersion: 4,
      reconnectPeriod: 1000,
      connectTimeout: 30 * 1000,
    };

    console.log("Connecting to broker...");
    const client = mqtt.connect(host, options);

    client.on("connect", () => {
      console.log("Connected");
      document.getElementById("status").innerHTML = "Connected to Broker";
      document.getElementById("status").style.color = "green";

      client.subscribe("robotics.upnjatim/#", {
        qos: 1
      });
    });

    client.on("message", function(topic, payload) {
      if (topic === "robotics.upnjatim/temp") {
        document.getElementById("temp").innerHTML = payload;
      } else if (topic === "robotics.upnjatim/humi") {
        document.getElementById("humi").innerHTML = payload;
      } else if (topic === "robotics.upnjatim/pot") {
        document.getElementById("pot").innerHTML = payload;
      } else if (topic === "robotics.upnjatim/srv") {
        let servo1 = $("#srv").data("ionRangeSlider")
        servo1.update({
          from: payload.toString()
        })
      } else if(topic === "robotics.upnjatim/led1"){
        if(payload == "on"){
          document.getElementById("label-led1on").classList.add("active");
          document.getElementById("label-led1off").classList.remove("active");
        } else {
          document.getElementById("label-led1on").classList.remove("active");
          document.getElementById("label-led1off").classList.add("active");
        }
      }

      if(topic.includes("robotics.upnjatim/status/")){
        document.getElementById(topic).innerHTML = payload;

        if(payload.toString() === "OFFLINE"){
          document.getElementById(topic).style.color = "red";
        } else if (payload.toString() === "ONLINE"){
          document.getElementById(topic).style.color = "green";
        }
      }
    });


    // Set Servo
    function publishSrv() {
      data = document.getElementById("srv").value;
      client.publish("robotics.upnjatim/srv", data, {qos:1, retain:true});
    }

    // Set LED
    function publishLed(value){
      if(document.getElementById("led1on").checked){
        data = "on";
      }
      if(document.getElementById("led1off").checked){
        data = "off";
      }
      client.publish("robotics.upnjatim/led1", data, {qos:1, retain:true});
    }
  </script>