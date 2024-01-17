<?php
/**
 * Funkcja: PokazPodstrone
 * Opis: Pobiera i zwraca zawartość strony na podstawie identyfikatora.
 */
function PokazPodstrone($id)
{
    // Bezpieczne użycie identyfikatora strony poprzez zastosowanie htmlspecialchars.
    $id_clear = htmlspecialchars($id);

    // Zapytanie do bazy danych w celu pobrania zawartości strony o danym identyfikatorze.
    $query = "SELECT * FROM page_list WHERE id = '$id_clear' LIMIT 1";
    $result = mysqli_query($query);
    $row = mysqli_fetch_array($result);

    // Sprawdzenie, czy strona została znaleziona, a następnie pobranie jej zawartości.
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>
