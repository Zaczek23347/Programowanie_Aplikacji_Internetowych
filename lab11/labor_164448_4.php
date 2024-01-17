<?php
    $nr_indeksu = '164448';
    $nr_grupy = '4';

    echo 'Kacper Żaczek ' . $nr_indeksu . ' grupa ' . $nr_grupy . '<br/><br/>';

    echo 'Zastosowanie metody include() <br/>';

    echo 'a) Metoda <b>include(), require_once()</b> <br/>';
    include 'vars.php';
    echo 'A '.$color.' '.$fruit.'<br/><br/>';
    
    echo 'b) Warunki <b>if, else, elseif, switch</b> <br/>';
    $a = 1;
    $b = 2;
    $c = 2;
    if ($a > $b)
        echo 'a jest większe od b<br/>';
    
    if ($b > $a) {
        echo 'b jest większe od a <br/>';
    } else {
        echo 'b nie jest większe od a<br/>';
    }
    
    if ($a < $b){
        echo 'a jest większe od b<br/>';
    } elseif ($a == $c) {
        echo 'a jest równe c<br/>';
    } else {
        echo 'b jest większe od a i c';
    }
    
    switch ($b) {
        case 0:
            echo 'i równe 0<br/>';
            break;
        case 1:
            echo 'i równe 1<br/>';
            break;
        case 2:
            echo 'i równe 2<br/>';
            break;
        case 3:
            echo 'i równe 3<br/>';
            break;
    }
    
    echo '<br/>';
    
    echo 'c) Pętla <b>while()</b> i <b>for()</b><br/>';
    $i = 1;
    echo 'while:<br/>';
    while($i <= 10){
        echo $i++;
    }
    
    echo '<br/><br/>';
    
    echo 'for:<br/>';    
    for ($j = 1; $j <= 10; $j++){
        echo $j;
    }
    
    echo '<br/><br/>';
    
    echo 'Typy zmiennych <b>$_GET, $_POST, $_SESSION</b><br/>';
    
    
    echo 'Hello '.htmlspecialchars($_GET["name"]).'!<br/>';
    if(isset($_POST["nazwa"])) {
        $imie = $_POST["nazwa"];
        echo 'Witaj, '.$imie. '!';
    } else {
        echo 'Pole nazwa nie zostało podane<br/>';
    }
    session_start();
    $_SESSION["newsession"]=$c;
?>