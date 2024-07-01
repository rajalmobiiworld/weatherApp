
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .weather-card {
            margin-top: 20px;
            background-color: #f8f9fa;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .weather-icon {
            width: 100px;
            height: 100px;
        }
        .card-title {
            font-size: 1.5rem;
        }
        .card-text {
            font-size: 1.2rem;
        }
        body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #87CEEB;
}

.weather-container {
    position: relative;
    width: 200px;
    height: 200px;
}

.sun {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    background-color: yellow;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    display: none;
}

.cloud, .rain, .snow {
    display: block;
}

.cloud {
    position: relative;
    top: 30%;
    left: 50%;
    width: 150px;
    height: 80px;
    background-color: white;
    border-radius: 50%;
    transform: translate(-50%, -50%);
}

.rain {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2px;
    height: 10px;
    background-color: blue;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 10px blue;
}

.snow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 10px;
    height: 10px;
    background-color: white;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 10px white;
}

    </style>
</head>
<?php
function get_weather_data($city_name, $api_key) {
    $base_url = "https://api.openweathermap.org/data/2.5/weather";
    $params = [
        'q' => $city_name,
        'appid' => $api_key,
        'units' => 'metric'
    ];
    $url = $base_url . '?' . http_build_query($params);

    $response = file_get_contents($url);
    if ($response !== FALSE) {
        $data = json_decode($response, true);
        if ($data['cod'] == 200) {
            $city = $data['name'];
            $current_temp = $data['main']['temp'];
            $weather_description = $data['weather'][0]['description'];
            $humidity = $data['main']['humidity'];
            $wind_speed = $data['wind']['speed'];
            $weather_icon = $data['weather'][0]['icon'];
            $icon_url = "http://openweathermap.org/img/wn/{$weather_icon}.png";

            return [
                'city' => $city,
                'current_temp' => $current_temp,
                'weather_description' => $weather_description,
                'humidity' => $humidity,
                'wind_speed' => $wind_speed,
                'weather_icon' => $icon_url
            ];
        } else {
            return null;
        }
    } else {
        return null;
    }
}

$api_key = "8ebfe88dedba91e4d1f979746e93df19";  // Replace with your OpenWeatherMap API key
$city_name = "sydney";
$weather_data = get_weather_data($city_name, $api_key);
?>
<body>
    <div class="container">
        <h1 class="text-center my-4">Weather Forecast</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card weather-card">
                    <div class="card-body text-center">
                        <h5 class="card-title" id="city"><?php echo  $weather_data['city'] ?></h5>
                        <p class="card-text">
                            <strong>Temperature:</strong> <span id="temperature"><?php echo  $weather_data['current_temp'] ?></span>°C<br>
                            <strong>Description:</strong> <span id="description"><?php echo  $weather_data['weather_description'] ?></span><br>
                            <strong>Humidity:</strong> <span id="humidity"><?php echo  $weather_data['humidity'] ?></span>%<br>
                            <strong>Wind Speed:</strong> <span id="wind-speed"><?php echo  $weather_data['wind_speed'] ?></span> m/s<br>
                        </p>
                        <img id="weather-icon" class="weather-icon" src="<?php echo  $weather_data['weather_icon'] ?>" alt="Weather Icon">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php /* animation*/ ?>
    <div class="weather-container">
        <div class="sun"></div>
        <div class="cloud"></div>
        <div class="rain"></div>
        <div class="snow"></div>
    </div>
    <i class="icon bigger" style="background: url(https://www.amcharts.com/wp-content/themes/amcharts4/css/img/icons/weather/animated/cloudy.png) no-repeat; background: none, url(https://www.amcharts.com/wp-content/themes/amcharts4/css/img/icons/weather/animated/cloudy.svg) no-repeat; background-size: contain;"></i>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
    const sun = document.querySelector(".sun");
    const cloud = document.querySelector(".cloud");
    const rain = document.querySelector(".rain");
    const snow = document.querySelector(".snow");

    function showSunny() {
        gsap.to([cloud, rain, snow], { duration: 0.5, autoAlpha: 0 });
        gsap.to(sun, { duration: 0.5, autoAlpha: 1, scale: 1.2, repeat: -1, yoyo: true });
    }

    function showCloudy() {
        gsap.to([sun, rain, snow], { duration: 0.5, autoAlpha: 0 });
        gsap.to(cloud, { duration: 0.5, autoAlpha: 1, scale: 1.2, repeat: -1, yoyo: true });
    }

    function showRainy() {
        gsap.to([sun, cloud, snow], { duration: 0.5, autoAlpha: 0 });
        gsap.to(rain, { duration: 0.5, autoAlpha: 1, y: 20, repeat: -1, yoyo: true });
    }

    function showSnowy() {
        gsap.to([sun, cloud, rain], { duration: 0.5, autoAlpha: 0 });
        gsap.to(snow, { duration: 0.5, autoAlpha: 1, y: 20, repeat: -1, yoyo: true });
    }

    // Initially show sunny weather
    showSunny();

    // Switch weather conditions every 5 seconds for demonstration
    setInterval(() => {
        const currentWeather = Math.floor(Math.random() * 4);
        switch (currentWeather) {
            case 0:
                showSunny();
                break;
            case 1:
                showCloudy();
                break;
            case 2:
                showRainy();
                break;
            case 3:
                showSnowy();
                break;
        }
    }, 5000);
});
    </script>
</body>
</html>
