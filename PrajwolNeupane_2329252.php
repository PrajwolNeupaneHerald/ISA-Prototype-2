<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Weather App</title>
</head>

<body>
  <!-- Prajwol Neupane -->
  <!-- 2329252 -->
  <?php


  $rest_api_url = 'https://api.openweathermap.org/data/2.5/weather?q=Renfrewshire&exclude=minutely,hourly&units=metric&appid=b26c79aaab8dc734c4a06a2b8f4593d0';


  // Reads the JSON file.
  $json_data = file_get_contents($rest_api_url);


  // Decodes the JSON data into a PHP array.
  $response_data = json_decode($json_data);


  // All the users data exists in 'data' object
  $user_data = $response_data;

  // Print data if need to debug

  // print_r($user_data->main->humidity);
  // It traverses the array and display user data


  $city = $user_data->name;
  $icon = $user_data->weather[0]->icon;
  $max_temp = $user_data->main->temp_max;
  $min_temp = $user_data->main->temp_min;
  $current_temp = $user_data->main->temp;
  $wind = $user_data->wind->speed;
  $humidity = $user_data->main->humidity;
  $description = $user_data->weather[0]->description;
  $response_time = $user_data->timezone;
  $date = new DateTime('now', new DateTimeZone('UTC'));
  $date->modify($response_time * 1000 . ' hours');
  $time = $date->format('H:i:s');
  $lat = $user_data->coord->lat;
  $lon = $user_data->coord->lon;


  $timeapi = "https://timeapi.io/api/TimeZone/coordinate?latitude=$lat&longitude=$lon";

  $json_time_data = file_get_contents($timeapi);
  $response_data = json_decode($json_time_data);
  $day_list = array("Sun" => "Sunday", "Mon" => "Monday", "Tue" => "Tuesday", "Wed" => "Wednesday", "Thu" => "Thursday", "Fri" => "Friday", "Sat" => "Saturday");
  $time_data = $response_data->currentLocalTime;
  $current_time = new DateTime($time_data);
  $year = $current_time->format('Y');
  $month = $current_time->format('M');
  $month_count = $current_time->format('m');
  $day = $day_list[$current_time->format('D')];
  $day_count = $current_time->format('d');
  $hour = $current_time->format('H');
  $minute = $current_time->format('m');
  $time = "$year $month$day_count $day $hour:$minute";


  $servername = "127.0.0.1:3307";
  $username = "root";
  $userpassword = "";
  $dbname = "weatherapp";
  $conn = mysqli_connect($servername, $username, $userpassword, $dbname);

  if (!$conn) {
    die("Connection failed : " . mysqli_connect_errno());
  }


  $upate_sql = "UPDATE pastdays SET maxtemp='$max_temp', mintemp='$min_temp' ,city='Renfrewshire' , wind='$wind' , icon='$icon' , description='$description' , humidity='$humidity' , date='$year-$month_count-$day_count' WHERE day='$day'";
  $conn->query($upate_sql);

  $sql = "SELECT * FROM pastdays ORDER BY date DESC;";
  $result = $conn->query($sql);

  ?>
  <?php
  ?>
  <div class="flex-col content">
    <div class="flex-col current-content">
      <h1><?php echo $city; ?></h1>
      <img src="http://openweathermap.org/img/wn/<?php echo $icon ?>@4x.png" />
      <h2>Description : <?php echo $description; ?></h2>
      <h3>Current Temperature : <?php echo $current_temp; ?> °C</h3>
      <h3>Time : <?php echo $time; ?> °C</h3>
      <h4>Min Temperature : <?php echo $min_temp; ?> °C</h4>
      <h4>Max Temperature : <?php echo $max_temp; ?> °C</h4>
      <h4>Humidity : <?php echo $humidity; ?> %</h4>
      <h4>Wind : <?php echo $wind; ?> m/s</h4>
    </div>
    <h1>Past Data at Renfrewshire, GB </h1>
    <table border="1px" cellspacing="0" cellpadding="10px">
      <tr>
        <th>Day</th>
        <th>Date</th>
        <th>Max Temp</th>
        <th>Min Temp</th>
        <th>Icon</th>
        <th>Description</th>
        <th>Humidity</th>
        <th>Wind Speed</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)) {
      ?>
        <tr>
          <td><?php echo $row['day']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['maxtemp']; ?> °C</td>
          <td><?php echo $row['mintemp']; ?> °C</td>
          <td><img src="https://openweathermap.org/img/wn/<?php echo $row['icon']; ?>@2x.png" /></td>
          <td><?php echo $row['description']; ?></td>
          <td><?php echo $row['humidity']; ?> %</td>
          <td><?php echo $row['wind']; ?> km/h </td>
        </tr>
      <?php } ?>
    </table>
  </div>
  <div class="bg-image" style="background: url(https://openweathermap.org/img/wn/<?php echo $icon; ?>@4x.png);background-size: 25% ;"></div>
</body>

</html>