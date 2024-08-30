<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dự Báo Thời Tiết</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">
    @include('style')
    
</head>

<body>

    <div class="hero">
        <div class="container">
            <h1>Dự Báo Thời Tiết</h1>
            <p>Nhập thành phố hoặc quốc gia để xem thông tin thời tiết</p>
            <form id="searchForm" method="POST" action="{{ route('weather.get') }}">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="searchInput" name="location" placeholder="Tìm kiếm..." required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="searchButton">Tìm kiếm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="weatherResult"></div>
        </div>
    </div>

    <div class="container">
        <div id="currentWeather" class="weather-card">
            @if(isset($data))
            <h1>Thời tiết tại {{$data['current']['location']['name']}} - {{$data['current']['location']['country']}} (Hôm nay)</h1>
            <p>Nhiệt độ: {{$data['current']['current']['temp_c']}}°C</p>
            <p>Tốc độ gió: {{$data['current']['current']['wind_kph']}} kph</p>
            <p>Độ ẩm: {{$data['current']['current']['humidity']}}%</p>
            <p>Trạng thái: {{$data['current']['current']['condition']['text']}}<img src="{{$data['current']['current']['condition']['icon']}}" alt=""></p>

            @endif
        </div>

        <div id="forecast" class="forecast mt-4">
            <div id="forecast" class="forecast mt-4">
                @if(isset($data))
                <h2>Dự báo thời tiết 4 ngày tới</h2>
                <ul class="weather-forecast" id="weatherForecast">
                    @foreach ($data['forecast'] as $day)
                    <li class="forecast-item">
                        <div class="forecast-date"><strong>{{ $day['date'] }}</strong></div>
                        <div class="forecast-details">
                            <span class="temperature">Nhiệt độ: {{ $day['day']['avgtemp_c'] }}°C</span>,
                            <!-- <span class="condition">Tình trạng: {{ $day['day']['condition']['text'] }}</span> -->
                            <span class="condition">
                                Tình trạng: {{ $conditions[$day['day']['condition']['text']] ?? $day['day']['condition']['text'] }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
                <button class="view-more" id="loadMoreForecast">Xem thêm</button>
            </div>

        </div>

        <div class="weather-card mt-5">
            @if(isset(Auth::user()->subscribe))
            <h3>Đã đăng ký nhận thông tin dự báo thời tiết ở {{Auth::user()->subscribe->location}}</h3>
            <div class="input-group-append">
                <button class="btn btn-warning" id="unsubscribeButton">Hủy đăng ký</button>
            </div>
            @else
            <h3>Đăng ký nhận bản tin dự báo thời tiết qua Gmail</h3>
            <form class="input-group" method="POST" action="{{ route('receive.forecast.mail') }}">
                @csrf
                <input type="text" class="form-control" id="send-weather-mail" name="location" placeholder="Nhập Thành Phố" require>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-success" id="subscribeButton">Đăng ký</button>
                </div>
            </form>
            @endif

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchButton').click(function(e) {
                // e.preventDefault();
                const query = $('#searchInput').val();
                console.log('{{ route("weather.get") }}');
                console.log(query);

                axios.post('{{ route("weather.get") }}', query)
                    .then(response => {
                        console.log(response);
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });

            $('#loadMoreForecast').click(function() {
                var location = "<?php echo $data['current']['location']['name'] ?? ''; ?>";
                if (location == '') {
                    alert('Chưa nhập thành phố');
                    return;
                }
                axios.get('{{ route("weather.viewmore.get") }}', {
                        params: {
                            location: location,
                        }
                    })
                    .then(response => {
                        console.log(response);

                        response.data.forEach((item) => {
                            var li = document.createElement('li');
                            li.className = 'forecast-item';

                            li.innerHTML = `
                                <div class="forecast-date"><strong>${item.date}</strong></div>
                                <div class="forecast-details">
                                    <span class="temperature">Nhiệt độ: ${item.day.avgtemp_c}°C</span>,
                                    <span class="condition">Tình trạng: ${item.day.condition.text}</span>
                                </div>
                            `;

                            document.getElementById('weatherForecast').appendChild(li);
                        })

                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });

            $('#subscribeButton').click(function(e) {
                // e.preventDefault();
                const location = $('#send-weather-mail').val();
                console.log(location);

                axios.post('{{ route("receive.forecast.mail") }}', location)
                    .then(response => {
                        console.log(response);
                        // window.location.reload();
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });

            $('#unsubscribeButton').click(function() {
                axios.get('{{ route("unreceive.forecast.mail") }}')
                    .then(response => {
                        console.log(response);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });

        });
    </script>

</body>

</html>