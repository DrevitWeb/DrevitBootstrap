<?php


namespace basics;


class Maths
{
    public static function circle_collision($h1, $h2)
    {
        $dx = $h1->left + $h1->radius - $h2->left - $h2->radius;
        $dy = $h1->top + $h1->radius - $h2->top - $h2->radius;
        $distance = sqrt($dx * $dx + $dy * $dy);

        return $distance < $h1->radius + $h2->radius && $h1 !== $h2;
    }

    public static function randInt($low, $up)
    {
        return rand($low, $up);
    }

    public static function geoDist($min, $max, $prob)
    {
        $q = 0;
        $p = pow($prob, 1 / ($max - $min));
        while (true)
        {
            $q = ceil(log(1 - rand(0, 1000) / 1000) / log($p)) + ($min - 1);
            if ($q <= $max)
            {
                return $q;
            }
        }
        return $q;
    }
}