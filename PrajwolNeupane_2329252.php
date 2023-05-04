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

  //Url for weather api
  $weather_api = 'https://api.openweathermap.org/data/2.5/weather?q=Renfrewshire&exclude=minutely,hourly&units=metric&appid=b26c79aaab8dc734c4a06a2b8f4593d0';


  // Reads the JSON file.
  $weather_json_data = file_get_contents($weather_api);


  // Decodes the JSON data into a PHP array.
  $weather_response_data = json_decode($weather_json_data);


  // All the users data exists in 'data' object
  $weather_data = $weather_response_data;

  //Getting required values and storing in variables
  $city = $weather_data->name;
  $icon = $weather_data->weather[0]->icon;
  $max_temp = $weather_data->main->temp_max;
  $min_temp = $weather_data->main->temp_min;
  $current_temp = $weather_data->main->temp;
  $wind = $weather_data->wind->speed;
  $humidity = $weather_data->main->humidity;
  $description = $weather_data->weather[0]->description;
  $lat = $weather_data->coord->lat;
  $lon = $weather_data->coord->lon;

  //URL for time api
  $time_api = "https://timeapi.io/api/TimeZone/coordinate?latitude=$lat&longitude=$lon";


  //Getting required values and storing in variables
  $time_json_data = file_get_contents($time_api);
  $time_data = json_decode($time_json_data);
  $day_list = array("Sun" => "Sunday", "Mon" => "Monday", "Tue" => "Tuesday", "Wed" => "Wednesday", "Thu" => "Thursday", "Fri" => "Friday", "Sat" => "Saturday");
  $time_data = $time_data->currentLocalTime;
  $current_time = new DateTime($time_data);
  $year = $current_time->format('Y');
  $month = $current_time->format('M');
  $month_count = $current_time->format('m');
  $day = $day_list[$current_time->format('D')];
  $day_count = $current_time->format('d');
  $hour = $current_time->format('H');
  $minute = $current_time->format('m');
  $time = "$year $day_count$month $day $hour:$minute";

  //Variables for Database 
  $servername = "127.0.0.1:3307";
  $username = "root";
  $userpassword = "";
  $dbname = "weatherapp";

  //Connection for Data Base
  $conn = mysqli_connect($servername, $username, $userpassword, $dbname);

  if (!$conn) {
    die("Connection failed : " . mysqli_connect_errno());
  }

  //Query for updating data into mysql
  $upate_sql = "UPDATE pastdays SET maxtemp='$max_temp', mintemp='$min_temp' ,city='Renfrewshire' , wind='$wind' , icon='$icon' , description='$description' , humidity='$humidity' , date='$year-$month_count-$day_count' WHERE day='$day'";
  $conn->query($upate_sql);

  //Query for getting data from database
  $sql = "SELECT * FROM pastdays ORDER BY date DESC;";
  //Storing data into result
  $result = $conn->query($sql);

  ?>
  <?php
  ?>
  <div class="flex-col content">
    <div class="flex-col current-content">
      <!-- Showing api response from php script -->
      <h1><?php echo $city; ?></h1>
      <img src="http://openweathermap.org/img/wn/<?php echo $icon ?>@4x.png" />
      <h2>Description : <?php echo $description; ?></h2>
      <h3>Current Temperature : <?php echo $current_temp; ?> °C</h3>
      <h3>Time : <?php echo $time; ?></h3>
      <h4>Min Temperature : <?php echo $min_temp; ?> °C</h4>
      <h4>Max Temperature : <?php echo $max_temp; ?> °C</h4>
      <h4>Humidity : <?php echo $humidity; ?> %</h4>
      <h4>Wind : <?php echo $wind; ?> m/s</h4>
    </div>
    <h2>Past Data at Renfrewshire, GB </h2>
    <table>
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
      <!-- Fetching data into table with while loop -->
        <tr>
          <td><?php echo $row['day']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['maxtemp']; ?> °C</td>
          <td><?php echo $row['mintemp']; ?> °C</td>
          <td><img src="https://openweathermap.org/img/wn/<?php echo $row['icon']; ?>@2x.png" /></td>
          <td><?php echo $row['description']; ?></td>
          <td><?php echo $row['humidity']; ?> %</td>
          <td><?php echo $row['wind']; ?> m/s </td>
        </tr>
      <?php } ?>
    </table>
    <div class="footer flex-col">
    <h3>Prajwol Neupane</h3>
    <h3>2329252 </h3>
  </div>
  </div>
  <div class="bg-image" style="background: url(https://openweathermap.org/img/wn/<?php echo $icon; ?>@4x.png);background-size: 25% ;"></div>
</body>

</html>