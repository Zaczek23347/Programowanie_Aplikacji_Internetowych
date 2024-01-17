<?php
session_start();
    /**
     * Klasa: Contact
    * Opis: Zarządza funkcjonalnością kontaktu, taką jak wyświetlanie formularza kontaktowego i wysyłanie maili.
    */
    class Contact
    {
        /**
        * Metoda: PokazKontakt
        * Opis: Wyświetla formularz kontaktowy.
        */
        public function PokazKontakt()
        {
            echo '
                <form method="post" action="contact.php?action=wyslij_mail_kontakt">
                    <label for="temat">Temat:</label>
                    <input type="text" name="temat" required>
                    <br>
                    <label for="tresc">Treść widomosci:</label>
                    <textarea name="tresc" rows="4" cols="50" required></textarea>
                    <br>
                    <label for="email">Twój e-mail:</label>
                    <input type="email" name="email" required>
                    <br>
                    <input type="submit" value="Wyślij">
                </form>
            ';
        }

        /**
 * Metoda: WyslijMailaKontakt
 * Opis: Wysyła maila z formularza kontaktowego do określonego odbiorcy.
 */
public function WyslijMailaKontakt($odbiorca)
{
    // Sprawdź, czy wszystkie pola formularza są wypełnione.
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        $this->PokazKontakt(); // Wyświetl ponownie formularz kontaktowy.
    } else {
        // Przygotuj dane maila.
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca;

        // Ustawienia dla funkcji mail().
        ini_set("sendmail_from", $mail['sender']);

        $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n Content-Transfer-Encoding: ";
        $header .= "X-Sender: <" . $mail['sender'] . ">\n";
        $header .= "X-priority: 3\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";

        // Wyślij maila.
        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        // Przywróć ustawienia sendmail_from do domyślnych.
        ini_restore("sendmail_from");

        echo '[wiadomosc_wyslana]';
    }
}


    /**
     * Metoda: PrzypomnijHaslo
     * Opis: Wysyła maila z przypomnieniem hasła do określonego odbiorcy.
     */
    public function PrzypomnijHaslo($odbiorca)
    {
        // Przygotuj dane maila.
        $mail['subject'] = 'Przypomnienie hasła';
        $mail['body'] = 'Twoje hasło to: admin123';
        $mail['sender'] = 'admin@example.com';
        $mail['recipient'] = $odbiorca;

        // Ustawienia dla funkcji mail().
        $header = "From: Przypomnienie hasła <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\n Content-Type: text/plain; charset=utf-8\n Content-Transfer-Encoding: ";
        $header .= "X-Sender: <" . $mail['sender'] . ">\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";

        // Wyślij maila.
        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo '[wiadomosc_wyslana]';
    }

    }

$kontakt = new Contact();

// Sprawdź, czy został ustawiony parametr "action" w żądaniu GET.
if (isset($_GET['action'])) {
    // Sprawdź wartość parametru "action".
    if ($_GET['action'] == 'wyslij_mail_kontakt') {
        $kontakt->WyslijMailaKontakt('odbiorca@example.com');
    } elseif ($_GET['action'] == 'przypomnij_haslo') {
        $kontakt->PrzypomnijHaslo('admin@example.com');
    } else {
        $kontakt->PokazKontakt();
    }
} else {
    // Jeśli brak parametru "action", po prostu wyświetl formularz kontaktowy.
    $kontakt->PokazKontakt();
}

// Zniszcz sesję (jeśli jest używana) po wykonaniu operacji.
session_destroy();
?>