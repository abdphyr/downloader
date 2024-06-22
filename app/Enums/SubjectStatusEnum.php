<?php

namespace App\Enums;

class SubjectStatusEnum extends BaseEnum
{
    const DRAFT = 10;
    const PUBLISHED = 20;

    public static function list($withText = false)
    {
        return $withText ?
            [
                self::DRAFT => __('enum.draft'),
                self::PUBLISHED => __('enum.published')
            ]
            :
            [
                self::DRAFT,
                self::PUBLISHED
            ];
    }
}
