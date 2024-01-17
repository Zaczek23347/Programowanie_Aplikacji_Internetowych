/**
 * Funkcja: gettheDate
 * Opis: Pobiera aktualną datę i aktualizuje element o identyfikatorze "data" na stronie.
 */
function gettheDate() {
    // Utwórz obiekt daty reprezentujący dzisiejszą datę.
    Todays = new Date();

    // Sformatuj datę w formie tekstu: MM / DD / YYYY.
    TheDate = "" + (Todays.getMonth() + 1) + " / " + Todays.getDate() + " / " + (Todays.getFullYear() - 2000);

    // Aktualizuj zawartość elementu o identyfikatorze "data" na stronie.
    document.getElementById("data").innerHTML = TheDate;
}



var timerID = null;
var timerRunning = false;

/**
 * Funkcja: stopclock
 * Opis: Zatrzymuje działanie zegara, jeśli był uruchomiony.
 */
function stopclock() {
    // Sprawdź, czy zegar był uruchomiony, a następnie wstrzymaj go.
    if (timerRunning) {
        clearTimeout(timerID);
    }

    // Oznacz, że zegar został zatrzymany.
    timerRunning = false;
}



/**
 * Funkcja: startclock
 * Opis: Rozpoczyna działanie zegara, zatrzymuje go, aktualizuje datę i pokazuje czas.
 */
function startclock() {
    // Zatrzymaj zegar, jeśli był uruchomiony.
    stopclock();

    // Pobierz aktualną datę.
    gettheDate();

    // Wyświetl aktualny czas.
    showtime();
}



/**
 * Funkcja: showtime
 * Opis: Wyświetla aktualny czas w formie 12-godzinnej z AM/PM, aktualizuje co sekundę.
 */
function showtime() {
    // Utwórz obiekt daty reprezentujący aktualny czas.
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Formatuj godziny do formy 12-godzinnej.
    var timeValue = "" + ((hours > 12) ? hours - 12 : hours);

    // Dodaj 0 przed minutami i sekundami, jeśli są mniejsze niż 10.
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;

    // Dodaj AM lub PM w zależności od godziny.
    timeValue += (hours >= 12) ? " P.M." : " A.M.";

    // Aktualizuj zawartość elementu o identyfikatorze "zegarek" na stronie.
    document.getElementById("zegarek").innerHTML = timeValue;

    // Uruchom funkcję showtime() co sekundę.
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}
