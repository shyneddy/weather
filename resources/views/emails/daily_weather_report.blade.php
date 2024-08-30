<!DOCTYPE html>
<html>

<head>
    <title>Dự Báo Thời Tiết</title>
</head>

<body>
    <h1>Dự Báo Thời Tiết Hôm Nay</h1>
    <h1>Thời tiết tại {{$currentWeatherData['location']['name']}} - {{$currentWeatherData['location']['country']}} (Hôm nay)</h1>
    <p>Nhiệt độ: {{$currentWeatherData['current']['temp_c']}}°C</p>
    <p>Tốc độ gió: {{$currentWeatherData['current']['wind_kph']}} kph</p>
    <p>Độ ẩm: {{$currentWeatherData['current']['humidity']}}%</p>
    <p>Trạng thái: {{$currentWeatherData['current']['condition']['text']}}<img src="{{$currentWeatherData['current']['condition']['icon']}}" alt=""></p>
</body>

</html>