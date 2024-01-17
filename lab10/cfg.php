<?php
    // Dane dostępowe do bazy danych
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    // Nawiązanie połączenia z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

    // Sprawdzenie poprawności połączenia
    if (!$link) {
        die('<b>Przerwane połączenie</b>: ' . mysqli_connect_error());
    }

    // Zamknięcie połączenia z bazą danych
    mysqli_close($link);
?>
