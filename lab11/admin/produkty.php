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


function PokazProdukty()
{
    global $link;

    $query = "SELECT * FROM products";
    $result = mysqli_query($link, $query);

    echo '<table class="product-table" id="tabelka" border=1>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Tytuł</th>';
    echo '<th>Opis</th>';
    echo '<th>Data Utworzenia</th>';
    echo '<th>Data Modyfikacji</th>';
    echo '<th>Data Wygaśnięcia</th>';
    echo '<th>Cena Netto</th>';
    echo '<th>Podatek VAT</th>';
    echo '<th>Ilość</th>';
    echo '<th>Status</th>';
    echo '<th>Kategoria</th>';
    echo '<th>Gabaryt</th>';
    echo '<th>Zdjęcie</th>';
    echo '</tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['tytul'] . '</td>';
        echo '<td>' . $row['opis'] . '</td>';
        echo '<td>' . $row['data_utworzenia'] . '</td>';
        echo '<td>' . $row['data_modyfikacji'] . '</td>';
        echo '<td>' . $row['data_wygasniecia'] . '</td>';
        echo '<td>' . $row['cena_netto'] . '</td>';
        echo '<td>' . $row['podatek_vat'] . '</td>';
        echo '<td>' . $row['ilosc'] . '</td>';
        echo '<td>' . ($row['status'] ? 'Aktywny' : 'Nieaktywny') . '</td>';
        echo '<td>' . $row['kategoria'] . '</td>';
        echo '<td>' . $row['gabaryt'] . '</td>';
		
        if ($row['zdjecie']) {
            $encodedImage = base64_encode(file_get_contents($row['zdjecie']));
            echo '<td><img src="data:image/jpeg;base64,' . $encodedImage . '"/></td>';
        } else {
            echo '<td>Brak zdjęcia</td>';
        }
        echo '</tr>';
    }

    echo '</table>';
}

function Formularz_Dodaj_Produkt()
{
    $add =
        '
    <div class="dodaj_produkt">
        <h1 class="heading">Dodaj Produkt:</h1>
        <div class="dodaj_produkt">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="dodaj_Kategorie">
                    <tr><td class="add_4t">[tytul]</td><td><input type="text" name="tytul_add" class="dodaj_produkt" /></td></tr>
                    <tr><td class="add_4t">[opis]</td><td><input type="text" name="opis_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[data_utworzenia]</td><td><input type="date" name="data_utworzenia_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[data_modyfikacji]</td><td><input type="date" name="data_modyfikacji_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[data_wygasniecia]</td><td><input type="date" name="data_wygasniecia_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[cena_netto]</td><td><input type="text" name="cena_netto_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[podatek_vat]</td><td><input type="text" name="podatek_vat_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[ilosc]</td><td><input type="text" name="ilosc_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[kategoria]</td><td><input type="text" name="kategoria_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[gabaryt]</td><td><input type="text" name="gabaryt_add" class="dodaj_produkt" /></td></tr>
					<tr><td class="add_4t">[zdjecie_sciezka]</td><td><input type="text" name="zdjecie_add" class="dodaj_produkt" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="P1_submit" class="dodaj_produkt_add" value="dodaj produkt" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}
//funkcja obslugujaca formularz dodawania produktu
function DodajNowyProdukt()
{
    global $link;
    if (isset($_POST["P1_submit"])) {
        $tytul_add = $_POST["tytul_add"];
        $opis_add = $_POST["opis_add"];
        $data_utworzenia_add = $_POST["data_utworzenia_add"];
        $data_modyfikacji_add = $_POST["data_modyfikacji_add"];
        $data_wygasniecia_add = $_POST["data_wygasniecia_add"];
        $cena_netto_add = $_POST["cena_netto_add"];
        $podatek_vat_add = $_POST["podatek_vat_add"];
        $ilosc_add = $_POST["ilosc_add"];
        $kategoria_add = $_POST["kategoria_add"];
        $gabaryt_add = $_POST["gabaryt_add"];
        $zdjecie_sciezka = $_POST["zdjecie_add"];

        $categoryCheckQuery = "SELECT id FROM categories WHERE id = $kategoria_add";
        $categoryCheckResult = mysqli_query($link, $categoryCheckQuery);

        if (!$categoryCheckResult || mysqli_num_rows($categoryCheckResult) === 0) {
            echo "Error: zle ID kategorii";
            return;
        }
        
        $status_add = "Nieaktywny";

        if ($ilosc_add > 0 && $data_wygasniecia_add >= date('Y-m-d')) {
            $status_add = "Aktywny";
        }
        
        $query = "INSERT INTO products (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc, status, kategoria, gabaryt, zdjecie) VALUES ('$tytul_add', '$opis_add', '$data_utworzenia_add', '$data_modyfikacji_add', '$data_wygasniecia_add', '$cena_netto_add', '$podatek_vat_add', '$ilosc_add', '$status_add', '$kategoria_add', '$gabaryt_add', '$zdjecie_sciezka')";

        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano produkt!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Błąd podczas dodawania produktu: " . mysqli_error($link);
        }
    }
}

function Formularz_Edytuj_Produkt()
{
    $edit =
        '
    <div class="Edytuj_produkt">
        <h1 class="heading">Edytuj Produkt:</h1>
        <div class="Edytuj_produkt">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="dodaj_produkt">
					<tr><td class="edit_4t">[id]</td><td><input type="text" name="id_edit" class="Edytuj_produkt" /></td></tr>
                    <tr><td class="edit_4t">[tytul]</td><td><input type="text" name="tytul_edit" class="Edytuj_produkt" /></td></tr>
                    <tr><td class="edit_4t">[opis]</td><td><input type="text" name="opis_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[data_utworzenia]</td><td><input type="date" name="data_utworzenia_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[data_modyfikacji]</td><td><input type="date" name="data_modyfikacji_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[data_wygasniecia]</td><td><input type="date" name="data_wygasniecia_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[cena_netto]</td><td><input type="text" name="cena_netto_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[podatek_vat]</td><td><input type="text" name="podatek_vat_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[ilosc]</td><td><input type="text" name="ilosc_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[kategoria]</td><td><input type="text" name="kategoria_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[gabaryt]</td><td><input type="text" name="gabaryt_edit" class="Edytuj_produkt" /></td></tr>
					<tr><td class="edit_4t">[zdjecie_sciezka]</td><td><input type="text" name="zdjecie_edit" class="Edytuj_produkt" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="P2_submit" class="dodaj_produkt_edit" value="Edytuj produkt" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $edit;
}

function EdytujProdukt()
{
    global $link;
    if (isset($_POST["P2_submit"])) {
		$id_edit = $_POST["id_edit"];
        $tytul_edit = $_POST["tytul_edit"];
        $opis_edit = $_POST["opis_edit"];
		$data_utworzenia_edit = $_POST["data_utworzenia_edit"];
		$data_modyfikacji_edit = $_POST["data_modyfikacji_edit"];
		$data_wygasniecia_edit = $_POST["data_wygasniecia_edit"];
		$cena_netto_edit = $_POST["cena_netto_edit"];
		$podatek_vat_edit = $_POST["podatek_vat_edit"];
		$ilosc_edit = $_POST["ilosc_edit"];
		$kategoria_edit = $_POST["kategoria_edit"];
		$gabaryt_edit = $_POST["gabaryt_edit"];
		$zdjecie_sciezka = $_POST["zdjecie_edit"];

        $categoryCheckQuery = "SELECT id FROM categories WHERE id = $kategoria_edit";
        $categoryCheckResult = mysqli_query($link, $categoryCheckQuery);

        if (!$categoryCheckResult || mysqli_num_rows($categoryCheckResult) === 0) {
            echo "Error: zle ID kategorii";
            return;
        }
		
		$status_edit = 0;

        if ($ilosc_edit > 0 && $data_wygasniecia_edit >= date('Y-m-d')) {
            $status_edit = 1;
        }
		
        $query = "UPDATE products SET tytul = '$tytul_edit', opis = '$opis_edit', data_utworzenia = '$data_utworzenia_edit', data_modyfikacji = '$data_modyfikacji_edit', data_wygasniecia = '$data_wygasniecia_edit', cena_netto = '$cena_netto_edit', podatek_vat = '$podatek_vat_edit', ilosc = '$ilosc_edit', status = '$status_edit', kategoria = '$kategoria_edit', gabaryt = '$gabaryt_edit', zdjecie = '$zdjecie_sciezka' WHERE id = $id_edit";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie zmodyfikowano produkt!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(); 
        } else {
            echo "Błąd podczas dodawania produktu: " . mysqli_error($link);
        }
    }
}

// wyswietlam formularz Usuniecia kategorii
function Formularz_Usun_Produkt()
{
    $del =
        '
    <div class="usun_produkt">
        <h1 class="heading">Usun Produkt:</h1>
        <div class="usun_produkt">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' .
        $_SERVER["REQUEST_URI"] .
        '">
                <table class="usun_produkt">
					<tr><td class="add_4t">[id produktu do usuniecia]</td><td><input type="text" name="produkt_id_delete" class="usun_produkt" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="P3_submit" class="usun_produkt" value="Usun Produkt" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $del;
}
//funkcja obslugujaca formularz usunieccia kategorii
function UsunProdukt()
{
    global $link;
    if (isset($_POST["P3_submit"])) {
		$id_del = $_POST["produkt_id_delete"];

        if (!empty($id_del)) {
            $query = "DELETE FROM products where id = '$id_del' LIMIT 1";
            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Pomyślnie usunieto produkt!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                echo "Błąd podczas usuwania kategorii: " . mysqli_error($link);
            }
        } else {
            echo "Nieprawidłowe ID produktu.";
			}
        }
}


//jesli uzytkownik nie jest zalogowany to wyswietlamy formularz logowania
if (isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
    echo " ";
} else {
    echo FormularzLogowania();
    Logowanie($link);
}
//wyswietlam panel CMS
if (isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
  // Wstrzymaj wysyłanie danych wyjściowych
  ob_start();

  header("Content-Type: text/html; charset=UTF-8");
  echo '<div class="lista_Produktow">';
  echo '<h2>Produkty:</h2>';
  PokazProdukty();
  echo '</div>';
  echo Formularz_Dodaj_Produkt();
  DodajNowyProdukt();
  echo Formularz_Edytuj_Produkt();
  EdytujProdukt();
  echo Formularz_Usun_Produkt();
  UsunProdukt();

  // Wznów wysyłanie danych wyjściowych
  ob_end_flush();
}

?>