//Prajwol Neupane
//2329252


//Varibales for documnents Element
var city = document.getElementById("city");
var temp = document.getElementById("temp");
var logo = document.getElementById("logo");
var searchBar = document.getElementById("search-bar");
var iconMain = document.getElementById("icon-main");
var maxTemp = document.getElementById("max_temp");
var minTemp = document.getElementById("min_temp");
var humidity = document.getElementById("humidity");
var wind = document.getElementById("wind");
var time = document.getElementById("time");


//Funcion for search bar
const changed = () => {
    getWeatherResonse(searchBar.value);
}

//Function to convert timezone offset into main time
function convertTime(offset) {
    //Variables for months
    var monthNames = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    d = new Date()
    localTime = d.getTime()
    localOffset = d.getTimezoneOffset() * 60000
    utc = localTime + localOffset
    var time = utc + (1000 * offset)
    date3 = new Date(time);
    var month = monthNames[date3.getMonth()]
    var day = date3.getDate();
    var year = date3.getFullYear();
    var time = date3.getHours();
    var min = date3.getMinutes();
    //Returning time
    return `${convertHour(time)}:${min} ${dayOrNight(time)} ${month} ${day}, ${year}`;
}
//Function to find day or night
function dayOrNight(hour){
    if(hour > 12){
        return "PM";
    }else{
        return "AM";
    }
}
//Function to convert time
function convertHour (hour){
    if(hour == 12){
        return 12;
    }else{
        return `0${hour % 12}`;
    }
}

//Fetching data from API
const getWeatherResonse = async (search) => {
    try {
        //Fetching URL
        const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${search}&exclude=minutely,hourly&units=metric&appid=b26c79aaab8dc734c4a06a2b8f4593d0`);
        //Converting data into json
        const data = await response.json();
        //Manipulating  DOM
        time.innerText = convertTime(data.timezone);
        wind.innerText = `Wind Speed : ${data.wind.speed} m/s`
        city.innerText = `${data.name}, ${data.sys.country}`;
        iconMain.innerText = data.weather[0].description;
        maxTemp.innerText = `Max Temperature : ${data.main.temp_max} °C`;
        minTemp.innerText = `Min Temperature : ${data.main.temp_min} °C`;
        humidity.innerText = `Humidity : ${data.main.humidity} %`
        temp.innerHTML = `Current Temperature <br/><span class='c'>${data.main.temp} °C</span>`;
        logo.src = `http://openweathermap.org/img/wn/${data.weather[0].icon}@4x.png`;
    } catch (e) {
        //Alerting user about envalid location
        alert("Please Enter valid Location");
        city.innerText = '-';
        iconMain.innerText = '-'
        maxTemp.innerText = `-`;
        minTemp.innerText = `-`;
        humidity.innerText = `-`
        temp.innerHTML = `-`;
        logo.src = ``;
    }
}
//Calling fetch function with default city
getWeatherResonse("Renfrewshire");