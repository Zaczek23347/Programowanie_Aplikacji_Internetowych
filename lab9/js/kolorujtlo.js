var computed = false;
var decimal = 0;


/**
 * Funkcja: convert
 * Opis: Konwertuje wartość z jednej jednostki na inną i aktualizuje wyświetlaną wartość.
 */
function convert(entryform, from, to) {
    // Pobierz indeks jednostki wyjściowej i jednostki docelowej z list rozwijanych.
    convertform = from.selectedIndex;
    convertto = to.selectedIndex;

    // Wykonaj konwersję i przypisz wynik do pola wyświetlania.
    entryform.display.value = (entryform.input.value * from[convertform].value / to[convertto].value);
}



/**
 * Funkcja: addChar
 * Opis: Dodaje znak do pola wejściowego, aktualizuje wartość i wykonuje konwersję.
 */
function addChar(input, character) {
    // Sprawdź, czy dodawany znak to kropka i czy zmienna decimal jest ustawiona na "0".
    // Lub czy znak nie jest kropką, aby uniknąć wielokrotnego dodawania kropki.
    if ((character === '.' && decimal === "0") || character !== '.') {
        // Jeśli pole jest puste lub zawiera "0", ustaw wartość na nowy znak, w przeciwnym razie dodaj do istniejącej wartości.
        (input.value === "" || input.value === "0") ? input.value = character : input.value += character;

        // Wywołaj funkcję konwersji z odpowiednimi parametrami.
        convert(input.form.inputfom.measure1, input.form.measure2);

        // Oznacz, że wartość została obliczona.
        computed = true;

        // Jeśli dodano kropkę, ustaw zmienną decimal na 1.
        if (character === '.') {
            decimal = 1;
        }
    }
}


/**
 * Funkcja: openVothcom
 * Opis: Otwiera nowe okno przeglądarki z określonymi parametrami.
 */
function openVothcom() {
    // Otwórz nowe okno przeglądarki bez paska narzędziowego, katalogów i paska menu.
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}


/**
 * Funkcja: clear
 * Opis: Czyści pola formularza i resetuje zmienną decimal.
 */
function clear(form) {
    // Ustaw wartości pól wejściowego i wyjściowego na 0.
    form.input.value = 0;
    form.display.value = 0;

    // Zresetuj zmienną decimal.
    decimal = 0;
}


/**
 * Funkcja: changeBackground
 * Opis: Zmienia kolor tła strony na podany kolor w formacie heksadecymalnym.
 */
function changeBackground(hexNumber) {
    // Ustaw kolor tła strony na podany kolor heksadecymalny.
    document.body.style.background = hexNumber;
}
