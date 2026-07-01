// ==================== API CONFIGURATION ====================
const API_KEY = '8d5fb5c999b6318ced26fdca13fbe359'; // OpenWeatherMap Free API Key
const BASE_URL = 'https://api.openweathermap.org/data/2.5';
const GEO_URL = 'https://api.openweathermap.org/geo/1.0';

// ==================== GLOBAL STATE ====================
let currentCity = 'London';
let currentLat = 51.5074;
let currentLon = -0.1278;
let temperatureUnit = 'C';
let windUnit = 'ms';
let refreshInterval = 10;
let weatherChart = null;
let autoRefreshTimer = null;
let temperatureHistory = [];

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    initializeApp();
    loadSettings();
    setupEventListeners();
});

function initializeApp() {
    showLoadingSpinner();
    
    if (navigator.geolocation) {
        setTimeout(() => {
            showGeoModal();
        }, 500);
    } else {
        fetchWeatherData();
    }
}

function setupEventListeners() {
    document.getElementById('citySearch').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            searchCity();
        }
    });
}

// ==================== GEOLOCATION ====================
function showGeoModal() {
    document.getElementById('geoModal').classList.add('active');
}

function closeGeoModal() {
    document.getElementById('geoModal').classList.remove('active');
    fetchWeatherData();
}

function enableGeolocation() {
    showLoadingSpinner();
    navigator.geolocation.getCurrentPosition(
        (position) => {
            currentLat = position.coords.latitude;
            currentLon = position.coords.longitude;
            document.getElementById('geoModal').classList.remove('active');
            fetchWeatherData();
        },
        (error) => {
            console.log('Geolocation error:', error);
            document.getElementById('geoModal').classList.remove('active');
            fetchWeatherData();
        }
    );
}

// ==================== SEARCH FUNCTIONALITY ====================
function searchCity() {
    const searchValue = document.getElementById('citySearch').value.trim();
    if (searchValue) {
        setLocation(searchValue);
    }
}

function setLocation(city) {
    showLoadingSpinner();
    currentCity = city;
    document.getElementById('citySearch').value = '';
    
    // Get coordinates from city name
    fetch(`${GEO_URL}/direct?q=${city}&limit=1&appid=${API_KEY}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                currentLat = data[0].lat;
                currentLon = data[0].lon;
                currentCity = data[0].name;
                fetchWeatherData();
            } else {
                showError('City not found. Please try again.');
                hideLoadingSpinner();
            }
        })
        .catch(error => {
            showError('Error searching city: ' + error.message);
            hideLoadingSpinner();
        });
}

// ==================== FETCH WEATHER DATA ====================
function fetchWeatherData() {
    Promise.all([
        fetchCurrentWeather(),
        fetchForecastData(),
        fetchAirQuality(),
        fetchMultipleLocations()
    ]).then(() => {
        hideLoadingSpinner();
        document.getElementById('mainContent').classList.add('active');
        startAutoRefresh();
    }).catch(error => {
        showError('Failed to fetch weather data: ' + error.message);
        hideLoadingSpinner();
    });
}

function fetchCurrentWeather() {
    const units = temperatureUnit === 'K' ? 'kelvin' : 'metric';
    return fetch(`${BASE_URL}/weather?lat=${currentLat}&lon=${currentLon}&units=${units}&appid=${API_KEY}`)
        .then(response => response.json())
        .then(data => {
            updateCurrentWeather(data);
            addTemperatureToHistory(data.main.temp);
        });
}

function fetchForecastData() {
    const units = temperatureUnit === 'K' ? 'kelvin' : 'metric';
    return fetch(`${BASE_URL}/forecast?lat=${currentLat}&lon=${currentLon}&units=${units}&appid=${API_KEY}`)
        .then(response => response.json())
        .then(data => {
            updateHourlyForecast(data);
            updateDailyForecast(data);
        });
}

function fetchAirQuality() {
    return fetch(`${BASE_URL}/air_pollution?lat=${currentLat}&lon=${currentLon}&appid=${API_KEY}`)
        .then(response => response.json())
        .then(data => {
            updateAirQuality(data);
        });
}

function fetchMultipleLocations() {
    const locations = ['New York', 'Tokyo', 'Sydney'];
    return Promise.all(
        locations.map(city => 
            fetch(`${GEO_URL}/direct?q=${city}&limit=1&appid=${API_KEY}`)
                .then(r => r.json())
                .then(geo => {
                    if (geo.length > 0) {
                        const units = temperatureUnit === 'K' ? 'kelvin' : 'metric';
                        return fetch(`${BASE_URL}/weather?lat=${geo[0].lat}&lon=${geo[0].lon}&units=${units}&appid=${API_KEY}`)
                            .then(r => r.json())
                            .then(weather => ({ city: geo[0].name, weather }));
                    }
                })
        )
    ).then(results => {
        updateLocationCards(results.filter(r => r));
    });
}

// ==================== UPDATE UI FUNCTIONS ====================
function updateCurrentWeather(data) {
    // Update location and date
    document.getElementById('cityName').textContent = `${data.name}, ${data.sys.country}`;
    const date = new Date();
    document.getElementById('weatherDate').textContent = date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Update temperature and weather
    const temp = convertTemperature(data.main.temp);
    document.getElementById('temperature').textContent = `${Math.round(temp)}°${temperatureUnit}`;
    document.getElementById('weatherDescription').textContent = data.weather[0].main;
    
    const feelsLike = convertTemperature(data.main.feels_like);
    document.getElementById('feelsLike').textContent = `${Math.round(feelsLike)}°${temperatureUnit}`;

    // Update weather icon
    const iconCode = data.weather[0].icon;
    document.getElementById('weatherIcon').src = `https://openweathermap.org/img/wn/${iconCode}@4x.png`;

    // Update weather details
    document.getElementById('humidity').textContent = `${data.main.humidity}%`;
    document.getElementById('windSpeed').textContent = `${convertWindSpeed(data.wind.speed).toFixed(2)} ${getWindUnit()}`;
    document.getElementById('pressure').textContent = `${data.main.pressure} mb`;
    document.getElementById('visibility').textContent = `${(data.visibility / 1000).toFixed(2)} km`;
    document.getElementById('precipitation').textContent = `${data.rain ? data.rain['1h'] : 0} mm`;
    document.getElementById('windDirection').textContent = getWindDirection(data.wind.deg);

    // Update sunrise/sunset
    const sunrise = new Date(data.sys.sunrise * 1000);
    const sunset = new Date(data.sys.sunset * 1000);
    document.getElementById('sunrise').textContent = sunrise.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    document.getElementById('sunset').textContent = sunset.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

    // Calculate day length
    const dayLength = (data.sys.sunset - data.sys.sunrise) / 3600;
    document.getElementById('dayLength').textContent = `${Math.floor(dayLength)} hours`;
}

function updateHourlyForecast(data) {
    const hourlyContainer = document.getElementById('hourlyForecast');
    hourlyContainer.innerHTML = '';

    // Get next 24 hours
    data.list.slice(0, 8).forEach(item => {
        const time = new Date(item.dt * 1000);
        const temp = convertTemperature(item.main.temp);
        const iconCode = item.weather[0].icon;

        const hourlyItem = document.createElement('div');
        hourlyItem.className = 'hourly-item';
        hourlyItem.innerHTML = `
            <div class="hourly-time">${time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</div>
            <div class="hourly-icon">
                <img src="https://openweathermap.org/img/wn/${iconCode}@2x.png" alt="Weather icon">
            </div>
            <div class="hourly-temp">${Math.round(temp)}°${temperatureUnit}</div>
        `;
        hourlyContainer.appendChild(hourlyItem);
    });
}

function updateDailyForecast(data) {
    const dailyContainer = document.getElementById('dailyForecast');
    dailyContainer.innerHTML = '';

    const dailyData = {};

    // Group by day
    data.list.forEach(item => {
        const date = new Date(item.dt * 1000).toDateString();
        if (!dailyData[date]) {
            dailyData[date] = [];
        }
        dailyData[date].push(item);
    });

    // Display next 5 days
    Object.keys(dailyData).slice(0, 5).forEach(date => {
        const items = dailyData[date];
        const temps = items.map(i => convertTemperature(i.main.temp));
        const high = Math.max(...temps);
        const low = Math.min(...temps);
        const icon = items[Math.floor(items.length / 2)].weather[0].icon;
        const description = items[Math.floor(items.length / 2)].weather[0].main;

        const dailyItem = document.createElement('div');
        dailyItem.className = 'daily-item';
        dailyItem.innerHTML = `
            <div class="daily-date">${new Date(date).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })}</div>
            <div class="daily-icon">
                <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="Weather icon">
            </div>
            <div class="daily-description">${description}</div>
            <div class="daily-temps">
                <div class="daily-high">${Math.round(high)}°</div>
                <div class="daily-low">${Math.round(low)}°</div>
            </div>
        `;
        dailyContainer.appendChild(dailyItem);
    });
}

function updateAirQuality(data) {
    const aqi = data.list[0].main.aqi;
    const components = data.list[0].components;

    const aqiLabels = ['Good', 'Fair', 'Moderate', 'Poor', 'Very Poor'];
    const aqiColors = ['#43e97b', '#f7b731', '#f5a623', '#ff6b6b', '#c92a2a'];

    document.getElementById('aqi-value').textContent = aqi;
    document.getElementById('aqi-value').style.color = aqiColors[aqi - 1];
    document.getElementById('aqi-label').textContent = aqiLabels[aqi - 1];

    const aqiDetails = document.getElementById('aqi-details');
    aqiDetails.innerHTML = `
        <div class="aqi-pollutant">
            <div class="aqi-pollutant-name">PM2.5</div>
            <div class="aqi-pollutant-value">${(components.pm2_5 || 0).toFixed(1)}</div>
        </div>
        <div class="aqi-pollutant">
            <div class="aqi-pollutant-name">PM10</div>
            <div class="aqi-pollutant-value">${(components.pm10 || 0).toFixed(1)}</div>
        </div>
        <div class="aqi-pollutant">
            <div class="aqi-pollutant-name">NO₂</div>
            <div class="aqi-pollutant-value">${(components.no2 || 0).toFixed(1)}</div>
        </div>
        <div class="aqi-pollutant">
            <div class="aqi-pollutant-name">O₃</div>
            <div class="aqi-pollutant-value">${(components.o3 || 0).toFixed(1)}</div>
        </div>
    `;
}

function updateLocationCards(locations) {
    const container = document.getElementById('locationCards');
    container.innerHTML = '';

    locations.forEach(loc => {
        if (loc) {
            const temp = convertTemperature(loc.weather.main.temp);
            const icon = loc.weather.weather[0].icon;
            const desc = loc.weather.weather[0].main;

            const card = document.createElement('div');
            card.className = 'location-card';
            card.innerHTML = `
                <div class="location-name">${loc.city}</div>
                <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="Weather icon">
                <div class="location-temp">${Math.round(temp)}°${temperatureUnit}</div>
                <div class="location-desc">${desc}</div>
            `;
            container.appendChild(card);
        }
    });
}

// ==================== UTILITY FUNCTIONS ====================
function convertTemperature(temp) {
    if (temperatureUnit === 'F') {
        return (temp * 9/5) + 32;
    } else if (temperatureUnit === 'K') {
        return temp + 273.15;
    }
    return temp; // Celsius
}

function convertWindSpeed(speed) {
    if (windUnit === 'kmh') {
        return speed * 3.6;
    } else if (windUnit === 'mph') {
        return speed * 2.237;
    }
    return speed; // m/s
}

function getWindUnit() {
    switch(windUnit) {
        case 'kmh': return 'km/h';
        case 'mph': return 'mph';
        default: return 'm/s';
    }
}

function getWindDirection(degrees) {
    const directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
    const index = Math.round(degrees / 22.5) % 16;
    return directions[index];
}

function addTemperatureToHistory(temp) {
    const convertedTemp = convertTemperature(temp);
    temperatureHistory.push(convertedTemp);
    if (temperatureHistory.length > 24) {
        temperatureHistory.shift();
    }
    updateTemperatureChart();
}

function updateTemperatureChart() {
    const ctx = document.getElementById('temperatureChart');
    if (!ctx) return;

    const now = new Date();
    const labels = [];
    for (let i = 23; i >= 0; i--) {
        const time = new Date(now);
        time.setHours(time.getHours() - i);
        labels.push(time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }));
    }

    const data = {
        labels: labels,
        datasets: [{
            label: `Temperature (°${temperatureUnit})`,
            data: temperatureHistory,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };

    if (weatherChart) {
        weatherChart.destroy();
    }

    weatherChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: document.body.classList.contains('dark-theme') ? '#fff' : '#000'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: document.body.classList.contains('dark-theme') ? '#fff' : '#000'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: document.body.classList.contains('dark-theme') ? '#fff' : '#000'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
}

// ==================== THEME TOGGLE ====================
function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    
    if (weatherChart) {
        updateTemperatureChart();
    }
}

// ==================== SETTINGS ====================
function changeTempUnit() {
    temperatureUnit = document.getElementById('tempUnit').value;
    localStorage.setItem('tempUnit', temperatureUnit);
    fetchWeatherData();
}

function changeWindUnit() {
    windUnit = document.getElementById('windUnit').value;
    localStorage.setItem('windUnit', windUnit);
    fetchWeatherData();
}

function setRefreshInterval() {
    refreshInterval = parseInt(document.getElementById('refreshInterval').value);
    localStorage.setItem('refreshInterval', refreshInterval);
    stopAutoRefresh();
    startAutoRefresh();
}

function loadSettings() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    temperatureUnit = localStorage.getItem('tempUnit') || 'C';
    windUnit = localStorage.getItem('windUnit') || 'ms';
    refreshInterval = parseInt(localStorage.getItem('refreshInterval')) || 10;

    document.getElementById('tempUnit').value = temperatureUnit;
    document.getElementById('windUnit').value = windUnit;
    document.getElementById('refreshInterval').value = refreshInterval;
}

// ==================== AUTO REFRESH ====================
function startAutoRefresh() {
    autoRefreshTimer = setInterval(() => {
        fetchWeatherData();
    }, refreshInterval * 60 * 1000);
}

function stopAutoRefresh() {
    if (autoRefreshTimer) {
        clearInterval(autoRefreshTimer);
    }
}

function refreshWeather() {
    showLoadingSpinner();
    fetchWeatherData();
}

// ==================== UI HELPERS ====================
function showLoadingSpinner() {
    document.getElementById('loadingSpinner').classList.add('active');
}

function hideLoadingSpinner() {
    document.getElementById('loadingSpinner').classList.remove('active');
}

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    errorEl.textContent = message;
    errorEl.classList.add('active');
    
    setTimeout(() => {
        errorEl.classList.remove('active');
    }, 5000);
}
