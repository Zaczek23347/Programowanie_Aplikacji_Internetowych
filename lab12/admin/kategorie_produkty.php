<style>
<?php include '../css/styleCMS.css'; ?>
</style>
<?php
session_start();


$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$link) echo '<b>przerwane połączenie </b>';
if (!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';


if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    // Dodaj przycisk do przenoszenia do strony z kategoriami oraz produktami
    echo '<form method="post" action="admin.php">
              <input type="submit" value="Podstrony" class="podstrony-button" />
          </form>';


}

/**
 * Funkcja generująca formularz logowania do panelu CMS.
*/
function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="logowanie">
                    <tr><td class="kog4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $wynik;
}

/*
/**
 * Funkcja do procesowania logowania użytkownika.
 */
function Logowanie($link)
{
    if (isset($_POST['x1_submit'])) {
        $mail = $_POST['login_email'];
        $haslo = $_POST['login_pass'];

        $query = "SELECT * FROM user_list WHERE user_name = '$mail' AND user_passwd = '$haslo' LIMIT 1";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['status'] = 1;
            session_write_close();
            header("Location: admin.php");
            exit();
            // Po przekierowaniu, poniższy kod nie zostanie wykonany, dlatego komentarz
            // echo "Logowanie udane! <br></br>";
        } else {
            echo "Niepoprawne dane logowania <br></br>";
            $_SESSION['status'] = 2;
        }
    }
}

function kategorie($matka = 0, $poziom = 0)
{
    global $link;

    $query = "SELECT * FROM categories WHERE matka = $matka";
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo str_repeat('&nbsp;&nbsp;&nbsp;', $poziom) . $row['id'] . '   ' . $row['nazwa'] . '<br>';

        // Rekurencyjne wywołanie funkcji dla podkategorii
        kategorie($row['id'], $poziom + 1);
    }
}

function Formularz_Dodaj_Kategorie()
{
    $add =
        '
    <div class="dodaj_Kategorie">
        <h1 class="heading">Dodaj Kategorie:</h1>
        <div class="dodaj_Kategorie">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="dodaj_Kategorie">
                    <tr><td class="add_4t">[matka]</td><td><input type="text" name="category_mother_add" class="dodaj_Kategorie" /></td></tr>
                    <tr><td class="add_4t">[nazwa]</td><td><input type="text" name="category_name_add" class="dodaj_Kategorie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="K1_submit" class="dodaj_Kategorie" value="dodaj Kategorie" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}
//funkcja obslugujaca formularz dodawania kategorii
function DodajNowaKategorie()
{
    global $link;
    if (isset($_POST["K1_submit"])) {
        $matka_add = $_POST["category_mother_add"];
        $nazwa_add = $_POST["category_name_add"];

        $query = "INSERT INTO categories (matka, nazwa) VALUES ('$matka_add', '$nazwa_add')";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano kategorie!";
            exit();
        } else {
            echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
        }
    }
}
// wyswietlam formularz edycji kategorii
function Formularz_Edytuj_Kategorie()
{
    $add =
        '
    <div class="edytuj_Kategorie">
        <h1 class="heading">Edytuj Kategorie:</h1>
        <div class="edytuj_Kategorie">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="edytuj_Kategorie">
                    <tr><td class="add_4t">[id kategorii edytowanej]</td><td><input type="text" name="category_id_edit" class="edytuj_Kategorie" /></td></tr>
                    <tr><td class="add_4t">[matka]</td><td><input type="text" name="category_mother_edit" class="edytuj_Kategorie" /></td></tr>
                    <tr><td class="add_4t">[nazwa]</td><td><input type="text" name="category_name_edit" class="edytuj_Kategorie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="K2_submit" class="edytuj_Kategorie" value="edytuj Kategorie" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}
//funkcja obslugujaca formularz edycji kategorii
function EdytujKategorie()
{
    global $link;
    if (isset($_POST["K2_submit"])) {
        $id_edit = $_POST["category_id_edit"];
        $matka_edit = $_POST["category_mother_edit"];
        $nazwa_edit = $_POST["category_name_edit"];

        if (!empty($id_edit)) {
            $query = "UPDATE categories SET matka = '$matka_edit', nazwa = '$nazwa_edit' WHERE id = $id_edit LIMIT 1";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie zaktualizowano kategorię!";
                exit();
            } else {
                echo "Błąd podczas aktualizacji kategorii: " . mysqli_error($link);
            }
        } else {
            echo "Nieprawidłowe ID kategorii.";
            }
        }
}

// wyswietlam formularz Usuniecia kategorii
function Formularz_Usun_Kategorie()
{
    $add =
        '
    <div class="usun_kategorie">
        <h1 class="heading">Usun Kategorie:</h1>
        <div class="usun_kategorie">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="usun_kategorie">
                    <tr><td class="add_4t">[id kategorii do usuniecia]</td><td><input type="text" name="category_id_delete" class="usun_kategorie" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="K3_submit" class="usun_kategorie" value="Usun Kategorie" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}
//funkcja obslugujaca formularz usunieccia kategorii
function UsunKategorie()
{
    global $link;
    if (isset($_POST["K3_submit"])) {
        $id_del = $_POST["category_id_delete"];

        if (!empty($id_del)) {
            $query = "DELETE FROM categories where id = '$id_del' OR matka = '$id_del' LIMIT 1";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie usunieto kategorię!";
                exit();
            } else {
                echo "Błąd podczas usuwania kategorii: " . mysqli_error($link);
            }
        } else {
            echo "Nieprawidłowe ID kategorii.";
            }
        }
}


if (isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
    echo '<div class="lista_kategorii">';
    echo '<h2>Kategorie:</h2>';
    kategorie();
    echo '</div>';
    echo Formularz_Dodaj_Kategorie();
    DodajNowaKategorie();
    echo Formularz_Edytuj_Kategorie();
    EdytujKategorie();
    echo Formularz_Usun_Kategorie();
    UsunKategorie();
}
if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    echo " ";
} else {
    echo FormularzLogowania();
    Logowanie($link);
}
?>
