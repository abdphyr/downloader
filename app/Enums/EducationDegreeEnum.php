<?php

namespace App\Enums;

class EducationDegreeEnum extends BaseEnum
{
    const MEDIUM = 1;
    const MEDIUM_SPECIAL = 2;
    const HIGH = 3;
    const MASTER = 4;

    public static function list($withText = false)
    {
        return ($withText) ? [
            self::MEDIUM => __('enum.medium'),
            self::MEDIUM_SPECIAL => __('enum.medium-special'),
            self::HIGH  => __('enum.high'),
            self::MASTER => __('enum.master'),
        ] : [
            self::MEDIUM,
            self::MEDIUM_SPECIAL,
            self::HIGH,
            self::MASTER,
        ];
    }
}
