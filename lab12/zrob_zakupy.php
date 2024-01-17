<link rel="stylesheet" href="css/style.css">
    <tr>
    <div id="navigation"><a href="index.php?idp=">Strona Główna</a></div>
</tr>
<br></br>
<?php

session_start();
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$link) echo '<b>przerwane połączenie </b>';
if (!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["dodaj_do_koszyka"])) {
        DodajDoKoszyka();
    } elseif (isset($_POST["edytuj_ilosc"])) {
        EdytujIloscWKoszyku();
    } elseif (isset($_POST["usun_z_koszyka"])) {
        UsunZKoszyka();
    } elseif (isset($_POST["zloz"])) {
        session_unset();
	    session_destroy();
		header("Location: index.php");
		exit();
    }
}

function PobierzDaneProduktu($id_prod, $link)
{
    $query = "SELECT * FROM products WHERE id = $id_prod";
    $result = mysqli_query($link, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        return false;
    }
}

function CzyProduktWKoszyku($id_prod)
{
    if (isset($_SESSION['count'])) {
        for ($x = 1; $x <= $_SESSION['count']; $x++) {
            if (isset($_SESSION[$x.'_1']) && $_SESSION[$x.'_1'] == $id_prod) {
                return $x; // Zwraca numer produktu w koszyku
            }
        }
    }
    return false; // Produkt nie istnieje w koszyku
}

function DodajDoKoszyka()
{
    global $link;

    $id_prod = $_POST['id'] ?? null;
    $cena = $_POST['cena'] ?? null;
    $ile_sztuk = $_POST['ilosc'] ?? 1;


    // Sprawdzenie, czy produkt już istnieje w koszyku
    $exists = CzyProduktWKoszyku($id_prod);
	$row = PobierzDaneProduktu($id_prod, $link);
	$ilosc_dostepna = $row['ilosc'];

    if ($exists) {
        // Produkt już istnieje w koszyku - zaktualizuj ilość
        $_SESSION[$exists.'_2'] += $ile_sztuk;
		$_SESSION[$exists.'_2'] = min($_SESSION[$exists.'_2'], $ilosc_dostepna);
    } else {
        // Jeśli produkt nie istnieje, dodaj nowy
        // Ustawienie licznika ilości produktów w koszyku
        if (!isset($_SESSION['count'])) {
            $_SESSION['count'] = 1;
        } else {
            $_SESSION['count']++;
        }

        // Przygotowanie danych nowego produktu
        $nr = $_SESSION['count'];
        $row = PobierzDaneProduktu($id_prod, $link);

        // Ustawienie kluczy dla nowego produktu
        $_SESSION[$nr.'_0'] = $nr;
        $_SESSION[$nr.'_1'] = $id_prod;
        $_SESSION[$nr.'_2'] = $ile_sztuk;
        $_SESSION[$nr.'_3'] = time();
        $_SESSION[$nr.'_4'] = $row['tytul'];
        $_SESSION[$nr.'_5'] = $cena;
        $_SESSION[$nr.'_6'] = $cena * $ile_sztuk;
        $_SESSION[$nr.'_7'] = $row['zdjecie'];
    }

    // Przekierowanie do koszyka
    header("Location: zrob_zakupy.php");
    exit();
}

function UsunZKoszyka()
{
    if (isset($_POST['id'])) {
        $id_prod = $_POST['id'];

        // Sprawdź czy produkt istnieje w koszyku
        $numer_produktu = CzyProduktWKoszyku($id_prod);

        if ($numer_produktu !== false) {
            // Produkt istnieje, usuń go z koszyka
            unset(
                $_SESSION[$numer_produktu.'_0'],
                $_SESSION[$numer_produktu.'_1'],
                $_SESSION[$numer_produktu.'_2'],
                $_SESSION[$numer_produktu.'_3'],
                $_SESSION[$numer_produktu.'_4'],
                $_SESSION[$numer_produktu.'_5'],
                $_SESSION[$numer_produktu.'_6'],
                $_SESSION[$numer_produktu.'_7']
            );
        }
    }

    // Przekierowanie z powrotem do koszyka
    header("Location: zrob_zakupy.php");
    exit();
}

function PokazProduktyKoszyk()
{
    global $link;

    $query = "SELECT * FROM products";
    $result = mysqli_query($link, $query);

    echo '<div id="zakupy">';
    echo '<table class="product-table" id="tabelka" border=1>';
    echo '<tr>';
    echo '<th>Tytuł</th>';
    echo '<th>Opis</th>';
    echo '<th>Cena Brutto</th>';
    echo '<th>Status</th>';
    echo '<th>Gabaryt</th>';
    echo '<th>Ilość dostępna</th>';
    echo '<th>Zdjęcie</th>';
    echo '<th>Dodaj do koszyka</th>';
    echo '</tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['tytul'] . '</td>';
        echo '<td>' . $row['opis'] . '</td>';

        $cenaBrutto = $row['cena_netto'] * (1 + ($row['podatek_vat']));
        echo '<td>' . number_format($cenaBrutto, 2) . '</td>';

        echo '<td>' . ($row['status'] ? 'Aktywny' : 'Nieaktywny') . '</td>';
        echo '<td>' . $row['gabaryt'] . '</td>';
        echo '<td>' . $row['ilosc'] . '</td>';

        echo '<td>';
        if ($row['zdjecie']) {
            $encodedImage = base64_encode(file_get_contents($row['zdjecie']));
            echo '<img src="data:image/jpeg;base64,' . $encodedImage . '" alt="Zdjęcie produktu"/>';
        } else {
            echo 'Brak zdjęcia';
        }
        echo '</td>';

        echo '<td>';
            // Jeśli produkt nie jest w koszyku, wyświetl pole do dodania do koszyka
            echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<input type="hidden" name="tytul" value="' . $row['tytul'] . '">';
            echo '<input type="hidden" name="cena" value="' . $cenaBrutto . '">';
            echo '<input type="number" name="ilosc" value="1" min="1">';
            echo '<input type="submit" name="dodaj_do_koszyka" value="Dodaj">';
            echo '</form>';
        }
        echo '</td>';

        echo '</tr>';

    echo '</table>';
    echo '</div>';
}

function EdytujIloscWKoszyku()
{
    if (isset($_POST['id'], $_POST['ilosc'])) {
        $id_prod = $_POST['id'];
        $nowa_ilosc = $_POST['ilosc'];

        // Sprawdź czy produkt istnieje w koszyku
        $numer_produktu = CzyProduktWKoszyku($id_prod);

        if ($numer_produktu !== false) {
            // Produkt istnieje, zaktualizuj ilość
            $_SESSION[$numer_produktu.'_2'] = $nowa_ilosc;
        }
    }

    // Przekierowanie z powrotem do koszyka
    header("Location: zrob_zakupy.php");
    exit();
}

function WyswietlZawartoscKoszyka()
{
    echo '<div id="koszyk">';
    echo '<h2>Zawartość koszyka</h2>';
    $suma = 0;

    if (isset($_SESSION['count'])) {
        echo '<ul>';
        for ($x = 1; $x <= $_SESSION['count']; $x++) {
            if (isset($_SESSION[$x.'_1'], $_SESSION[$x.'_2'], $_SESSION[$x.'_5'])) {
                $suma += $_SESSION[$x.'_6'] * $_SESSION[$x.'_2'];
                echo '<li>';
                echo '<span>' . $_SESSION[$x.'_4'] . ' - Ilość: ' . $_SESSION[$x.'_2'] . '</span>';
                echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">
                        <input type="hidden" name="id" value="' . $_SESSION[$x.'_1'] . '">
                        <input type="number" name="ilosc" value="' . $_SESSION[$x.'_2'] . '" min="1">
                        <input type="submit" name="edytuj_ilosc" value="Zaktualizuj">
                    </form>
                    <form method="post" action="' . $_SERVER['PHP_SELF'] . '">
                        <input type="hidden" name="id" value="' . $_SESSION[$x.'_1'] . '">
                        <input type="submit" name="usun_z_koszyka" value="Usuń z koszyka">
                    </form>';
                echo '</li>';
            }
        }
        echo '</ul>';
    } else {
        echo 'Koszyk jest pusty';
    }
    echo '<p>Suma do zapłaty: ' . number_format($suma, 2) . '</p>';
	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">
            <input type="submit" name="zloz" value="Złóż zamówienie">
          </form>';
    echo '</div>';
    echo '</div>';
}
PokazProduktyKoszyk();
WyswietlZawartoscKoszyka();
?>