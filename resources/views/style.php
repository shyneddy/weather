<style>
    body {
        background-color: cadetblue;
        font-family: Arial, sans-serif;
    }

    .hero {
        background: url('https://source.unsplash.com/1600x900/?weather') no-repeat center center;
        background-size: cover;
        color: white;
        padding: 60px 0;
        text-align: center;
    }

    .weather-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin: 20px 0;
    }

    /* .forecast {
            display: none;
        } */

    @media (max-width: 576px) {
        .hero {
            padding: 30px 0;
        }

        .weather-card {
            margin: 10px 0;
        }
    }

    .forecast {
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .forecast h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .weather-forecast {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .forecast-item {
        border-bottom: 1px solid #eee;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .forecast-item:last-child {
        border-bottom: none;
    }

    .forecast-date {
        font-weight: bold;
        font-size: 1.2em;
    }

    .forecast-details {
        color: #555;
    }

    .temperature {
        color: #e67e22;
    }

    .condition {
        color: #3498db;
    }

    .view-more {
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: block;
        width: 100%;
        text-align: center;
    }

    .view-more:hover {
        background-color: #2980b9;
    }
</style>