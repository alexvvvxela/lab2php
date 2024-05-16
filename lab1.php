<?php
function znaki($zn) {
    return in_array($zn, ['+', '-', '*', '/']);
}

function calculate($primer) {
    if (!preg_match('/^[0-9\-\+\*\/\(\) ]+$/', $primer)) {
        return "Ошибка";
    }

    $primer = str_replace(' ', '', $primer);

    $vvod = [];
    $Stack = [];

    for ($i = 0; $i < strlen($primer); $i++) {
        $zn = $primer[$i];

        if (znaki($zn) || $zn === '(' || $zn === ')') {
            if ($zn === '(') {
                array_push($Stack, $zn);
            } elseif ($zn === ')') {
                while (end($Stack) !== '(') {
                    $znacen = array_pop($Stack);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_pop($Stack); 
            } elseif (znaki($zn)) {
                while (!empty($Stack) && znaki2(end($Stack)) >= znaki2($zn)) {
                    $znacen = array_pop($Stack);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_push($Stack, $zn);
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

function znaki2($zn) {
    if ($zn === '+' || $zn === '-') {
        return 1;
    } elseif ($zn === '*' || $zn === '/') {
        return 2;
    }
    return 0;
}

function otvet($per1, $per2, $zn) {
    switch ($zn) {
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