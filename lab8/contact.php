<?php
session_start();
    class Contact
    {
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

        public function WyslijMailaKontakt($odbiorca)
        {
            if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
            {
                echo '[nie_wypelniles_pola]';
                echo PokazKontakt();
            }
            else
            {
                $mail['subject'] = $_POST['temat'];
                $mail['body'] = $_POST['tresc'];
                $mail['sender'] = $_POST['email'];
                $mail['reciptient'] = $odbiorca;

                ini_set("sendmail_from", $mail['sender']);

                $header = "Form: Formularz kontaktowy <".$mail['sender'].">\n";
                $header .= "MIME-Version: 1.0\n Content-Type: text/plain: charset-uft-8\n Content-Transfer-Encoding: ";
                $header .= "X-Sender: <". $mail['sender'].">\n";
                $header .= "X-pririty: 3\n";
                $header .= "Return_Path: <". $mail['sender'].">\n";

                mail($mail['reciptient'],$mail['subject'],$mail['body'],$header);

                ini_restore("sendmail_from");

                echo '[wiadomosc_wyslana]';
            }
        }

        public function PrzypomnijHaslo($odbiorca)
        {
            $mail['subject'] = 'Przypomnienie hasła';
            $mail['body'] = 'Twoje hasło to: admin123';
            $mail['sender'] = 'admin@example.com';
            $mail['reciptient'] = $odbiorca;

            $header = "From: Przypomnienie hasła <".$mail['sender'].">\n";
            $header .= "MIME-Version: 1.0\n Content_Type: text/plain: charset=utf-8\n Content_Transfer_Encoding: ";
            $header .= "X-Sender: <". $mail['sender'].">/n";
            $header .= "X-Priority: 3\n";
            $header .= "Return_Path: <". $mail['sender'].">\n";

            mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);

            echo '[wiadomosc_wyslana]';
        }
    }

    $kontakt = new Contact();

    if (isset($_GET['action'])) {
        if($_GET['action'] == 'wyslij_mail_kontakt') {
                $kontakt->WyslijMailaKontakt('odbiorca@example.com');
            } elseif ($_GET['action'] == 'przypomnij_haslo') {
                $kontakt->PrzypomnijHaslo('admin@example.com');
            } else {
                $kontakt->PokazKontakt();
            }
    } else {
        $kontakt->PokazKontakt();
    }
    session_destroy();
?>