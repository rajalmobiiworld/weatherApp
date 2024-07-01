<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link  href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        header {
            position: relative;
            display: block;
            width: 100%;
            padding: 1rem 0;
            z-index: 1020;
            background-color: #149edc;
            text-transform: uppercase;
        }
        section {
            position: relative;
            display: block;
            padding: 2rem 0;
        }

        @media (max-width: 767px) {
            h1 {   
                font-size: 2rem;
                margin: 1rem 0 !important;
            }
        }
        
        .btn-primary { 
            background: #000000;
            border: transparent;
            height: 50px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .btn-primary:hover,  .btn-primary:focus, .btn-primary:active { background: #149edc; }
        .weather-card {
            margin-top: 20px;
            background-color: #149edc;
            border: none;
            box-shadow: 0 15px 20px rgba(0,0,0,0.5);
            color: #ffffff;
        }
        h1{
            color: #ffffff;
        }

        .card-title {
            font-size: 2.5rem;
            background: rgba(255, 255, 255, 0.7);
            padding: 10px 40px;
            width: max-content;
            margin: 20px auto;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            color: #149edc;
        }
        .card-text {
            font-size: 1.2rem;
        }
        .city-form {
            position: relative;
            display: block;
        }
        .city-form label {
            color: #ffffff;
            font-size: 1.5rem;
        }
        .temp-block {
            margin-bottom: 1rem;
        }
        .temperature {
            font-size: 4rem;
            font-weight: 500;
            text-align: center;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }
        .deg {
            font-size: 2rem;
            padding-inline-start: 0.5rem;
        }
        .description {
            text-transform: capitalize;
            font-size: 1.5rem;
        }
        .humidity {
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ffffff;
        }
        .weather-icon {
            width: 100px;
            height: 100px;
        }
        .weather-icon-1 {
            width: 100px;
            height: auto;
            animation: updown 3s ease infinite;
        }
        .main-bg {
            background-image: url("images/default.jpg");
            background-size: cover;
            height: calc(100vh - 120px);
        }
        @media (max-width: 767px) {
            .main-bg {  height: 100%; }
        }
        
        .main-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0, 0.3);
            z-index: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="text-center my-4">Weather Forecast</h1>
        </div>
    </header>
    <section class="main-bg">
        <div class="container">
            <form class="city-form" method="post" action="">
                <div class="form-group">
                    <label for="city">Enter City Name:</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <button type="submit" class="btn btn-primary">Get Weather</button>
            </form>
        
            <?php
            $city_name = htmlspecialchars('Dubai');
            if (($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['city'])) || (!empty($city_name))) {
                if (!empty($_POST['city'])) $city_name = htmlspecialchars($_POST['city']);
                $api_key = "8ebfe88dedba91e4d1f979746e93df19"; 

                function get_weather_data($city_name, $api_key) {
                    $base_url = "https://api.openweathermap.org/data/2.5/weather";
                    $params = [
                        'q' => $city_name,
                        'appid' => $api_key,
                        'units' => 'metric'
                    ];
                    $url = $base_url . '?' . http_build_query($params);
                    $response = file_get_contents($url);
                    return json_decode($response, true);
                }

                $weather_data = get_weather_data($city_name, $api_key);

                if ($weather_data && $weather_data['cod'] == 200) {
                    $city = $weather_data['name'];
                    $current_temp = $weather_data['main']['temp'];
                    $weather_description = $weather_data['weather'][0]['description'];
                    $humidity = $weather_data['main']['humidity'];
                    $wind_speed = $weather_data['wind']['speed'];
                    $weather_icon = $weather_data['weather'][0]['icon'];
                    $icon_url = "http://openweathermap.org/img/wn/{$weather_icon}.png";
                ?>
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 col-xl-6">
                            <div class="card weather-card">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <h5 class="card-title" id="city"><?php echo $city; ?></h5>
                                            <div class="description"><?php echo $weather_description; ?></div>
                                            <img class="weather-icon" src="<?php echo $icon_url; ?>" alt="Weather Icon">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="card-text">
                                                <div class="temp-block">
                                                    <span class="temperature"><?php echo $current_temp; ?></span><span class="deg">°C</span>
                                                </div>
                                                <div class="humidity"><strong>Humidity:</strong> <span><?php echo $humidity; ?></span>%</div>
                                                
                                                <div class="wind"><strong>Wind Speed:</strong> <span><?php echo $wind_speed; ?></span> m/s </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<div class="alert alert-danger">Unable to get weather data for the specified city. Please check the city name and try again.</div>';
                }
            }
            ?>
        </div>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
