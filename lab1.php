<?php
function znaki($zn):int
{
    return in_array($zn, ['+', '-', '*', '/']);
}

function calculate($primer): float
{
    if (!preg_match('/^[0-9\-\+\*\/\(\) ]+$/', $primer)) {
        return "Ошибка";
    }

    $primer = str_replace(' ', '', $primer);

    $vvod = [];
    $steck = [];

    for ($i = 0; $i < strlen($primer); $i++) {
        $zn = $primer[$i];

        if (znaki($zn) || $zn === '(' || $zn === ')') {
            if ($zn === '(') {
                array_push($steck, $zn);
            } elseif ($zn === ')') {
                while (end($steck) !== '(') {
                    $znacen = array_pop($steck);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_pop($steck);
            } elseif (znaki($zn)) {
                while (!empty($steck) && znaki2(end($steck)) >= znaki2($zn)) {
                    $znacen = array_pop($steck);
                    $per2 = array_pop($vvod);
                    $per1 = array_pop($vvod);
                    $result = otvet($per1, $per2, $znacen);
                    array_push($vvod, $result);
                }
                array_push($steck, $zn);
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

    while (!empty($steck)) {
        $znacen = array_pop($steck);
        $per2 = array_pop($vvod);
        $per1 = array_pop($vvod);
        $result = otvet($per1, $per2, $znacen);
        array_push($vvod, $result);
    }

    return reset($vvod);
}

function znaki2($zn):float
{
    if ($zn === '+' || $zn === '-') {
        return 1;
    } elseif ($zn === '*' || $zn === '/') {
        return 2;
    }
    return 0;
}
function otvet($per1, $per2, $zn):float
{
    switch ($zn) {
        case '+':
            return $per1 + $per2;
        case '-':
            return $per1 - $per2;
        case '*':
            return $per1 * $per2;
        case '/':
            if ($per2 === 0.0) {  
                echo "Ошибка";
                exit;
            } else {
                return $per1 / $per2;
            }
    }
}

echo "Введите пример: ";
$primer = "123/0+1";
echo calculate($primer);
