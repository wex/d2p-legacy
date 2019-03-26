<?php

class DateTimeEx extends DateTime
{
    public function firstMonday()
    {
        $now = clone $this;
        $now->setDate($now->format('Y'), $now->format('m'), 1);

        while ($now->format('N') > 1) {
            $now->modify('-1 day');
        }

        return $now;
    }

    public function lastSunday()
    {
        $now = clone $this;
        $now->setDate($now->format('Y'), $now->format('m'), 1);
        $now->modify('+1 month');
        $now->modify('-1 day');

        while ($now->format('N') < 7) {
            $now->modify('+1 day');
        }

        return $now;
    }

    public function clone()
    {
        $now = clone $this;

        return $now;
    }
}