<?php

session_start();

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$link) echo '<b>przerwane połączenie </b>';
if (!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';

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
            echo "Logowanie udane! <br></br>";
        } else {
            echo "Niepoprawne dane logowania <br></br>";
            $_SESSION['status'] = 2;
        }
    }
}



function ListaPodstron($link)
{
    if (!isset($_SESSION['status']) || $_SESSION['status'] == 1) {
        $query = "SELECT * FROM page_list ORDER BY id ASC";
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            echo $row['id'] . ' ' . $row['page_title'] . '<br/>';
        }
    }
}

function FormularzEdycji()
{
    $edit = '
    <div class="edycja">
        <h1 class="heading">Edycja:</h1>
        <div class="edycja">
            <form method="post" name="EditForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="edycja">
                    <tr><td class="edit_4t">[id_strony_edytowanej]</td><td><input type="text" name="id_strony" class="edycja" /></td></tr>
                    <tr><td class="edit_4t">[tytul]</td><td><input type="text" name="page_title" class="edycja" /></td></tr>
                    <tr><td class="edit_4t">[tresc strony]</td><td><input type="text" name="page_content" class="edycja" /></td></tr>
                    <tr><td class="edit_4t">[czy_aktywna]</td><td><input type="checkbox" name="status" class="edycja" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x2_submit" class="edycja" value="zmien" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $edit;
}

function EdytujPodstrone()
{
    global $link;

    if (isset($_POST['x2_submit'])) {
        $id = $_POST['id_strony'];
        $tytul = $_POST['page_title'];
        $tresc = $_POST['page_content'];
        $status = isset($_POST['status']) ? 1 : 0;

        if (!empty($id)) {
            $query = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = $status WHERE id = $id LIMIT 1";

            $result = mysqli_query($link, $query);

            if ($result) {
                echo "Edycja zakończona pomyślnie!";
                header("Location: admin.php");
                exit();
            } else {
                echo "Błąd podczas edycji: " . mysqli_error($link);
            }
        }
    }
}


function FormularzDodawania()
{
    $add = '
    <div class="dodaj">
        <h1 class="heading">Dodaj Strone:</h1>
        <div class="dodaj">
            <form method="post" name="AddForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="dodaj">
                    <tr><td class="add_4t">[tytul]</td><td><input type="text" name="page_title_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t">[tresc strony]</td><td><input type="text" name="page_content_add" class="dodaj" /></td></tr>
                    <tr><td class="add_4t">[czy_aktywna]</td><td><input type="checkbox" name="status_add" class="dodaj" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x3_submit" class="dodaj" value="dodaj" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $add;
}

function DodajNowaPodstrone()
{
    global $link;
    if (isset($_POST['x3_submit'])) {
        $tytul = $_POST['page_title_add'];
        $tresc = $_POST['page_content_add'];
        $status = isset($_POST['status_add']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', $status)";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie dodano podstronę!";
            header("Location: admin.php");
            exit();
        } else {
            echo "Błąd podczas dodawania podstrony: " . mysqli_error($link);
        }
    }
}


function FormularzUsuwania()
{
    $remove = '
    <div class="usun">
        <h1 class="heading">Usun Strone:</h1>
        <div class="usun">
            <form method="post" name="DeleteForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                <table class="usun">
                    <tr><td class="rem_4t">[id]</td><td><input type="text" name="id_remove" class="usun" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x4_submit" class="usun" value="usun" /></td></tr>
                </table>
            </form>
        </div>
    </div>
    ';

    return $remove;
}

function UsunPodstrone()
{
    global $link;
    if (isset($_POST['x4_submit'])) {
        $id = $_POST['id_remove'];

        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "Pomyślnie usunięto podstronę!";
            header("Location: admin.php");
            exit();
        } else {
            echo "Błąd podczas usuwania podstrony: " . mysqli_error($link);
        }
    }
}

if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    echo " ";
} else {
    echo FormularzLogowania();
    Logowanie($link);
}

if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    ListaPodstron($link);
    echo FormularzEdycji();
    EdytujPodstrone();
    echo FormularzDodawania();
    DodajNowaPodstrone();
    echo FormularzUsuwania();
    UsunPodstrone();
}

?>