<?php
function znaki($c) {
    return in_array($c, ['+', '-', '*', '/']);
}

function calculate($primer):string {
    if (!preg_match('/^[0-9\-\+\*\/\(\) ]+$/', $primer)) {
        return "Ошибка";
    }

    $primer = str_replace(' ', '', $primer);

    $vvod = [];
    $Stack = [];

    for ($i = 0; $i < strlen($primer); $i++) {
        $znac = $primer[$i];

        if (znaki($znac) || $znac === '(' || $znac === ')') {
            if ($znac === '(') {
                array_push($Stack, $znac);
            } elseif ($znac === ')') {
                while (end($Stack) !== '(') {
                    $znacen = array_pop($Stack);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_pop($Stack); 
            } elseif (znaki($znac)) {
                while (!empty($Stack) && znaki2(end($Stack)) >= znaki2($znac)) {
                    $znacen = array_pop($Stack);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_push($Stack, $znac);
            }
        } else {
            $number = '';
            while ($i < strlen($primer) && is_numeric($primer[$i])) {
                $number .= $primer[$i];
                $i++;
            }
            $i--;
            array_push($vvod, (float)$number);
        }
    }

    while (!empty($Stack)) {
        $znacen = array_pop($Stack);
        $per2 = array_pop($vvod);
        $per1 = array_pop($vvod);
        $result = otvet($per1, $per2, $znacen);
        array_push($vvod, $result);
    }

    return reset($vvod);
}

function znaki2($znac) {
    if ($znac === '+' || $znac === '-') {
        return 1;
    } elseif ($znac === '*' || $znac === '/') {
        return 2;
    }
    return 0;
}

function otvet($per1, $per2, $znac) {
    switch ($znac) {
        case '+':
            return $per1 + $per2;
        case '-':
            return $per1 - $per2;
        case '*':
            return $per1 * $per2;
        case '/':
            if ($per2 == 0) {
                return "Ошибка";
            }
            return $per1 / $per2;
    }
}

echo "Введите пример: ";
$primer = readline();
echo calculate($primer); 
?>