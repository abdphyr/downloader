<?php

namespace App\Partials\Service\Traits;

trait ServiceProperties
{
    /**
     * @var mixed
     */
    public $manyRelation;

    /**
     * With trashed
     * @var bool
     */
    protected $withTrashed = false;

    /**
     * Model Translation class
     * @var mixed
     */
    protected $translation;

    /**
     * Export file class
     * @var mixed
     */
    protected $exportClass;

    /**
     * Import file class
     * @var mixed
     */
    protected $importClass;

    /**
     * @var mixed
     */
    protected $extraColumn;

    /**
     * Eager loading uchun actionda ko'rsatiladiga relationlar.
     * @var array
     */
    public array $relations = [];

    /**
     * Eager loading uchun actionda oddiy php array holatda ko'rsatiladigan va keyin ORM ga parse qilinadigan relationlar.
     * @var array
     */
    public array $willParseToRelation = [];

    /**
     * Umumiy conditiondan tashqari maxsus conditionlar. Actionda yoziladi.
     * @var array
     */
    public array $conditions = [];

    /**
     * Modeldagi like filter qilinadigan fieldlar ro'yxati. Translation tabledagi fieldlar bundab mustasno.
     * @var array
     */
    public array $likableFields = [];

    /**
     * Databasega yozilishidan oldin JSON ga o'tkaziladigan fieldlar ro'yxati
     * @var array
     */
    public array $serializingToJsonFields = [];

    /**
     * Modelning translation table idagi like filter qilinadigan fieldlar ro'yxati.
     * @var array
     */
    public array $translationFields = [];

    /**
     * Modeldagi to'g'ridan to'gri equal filter qilinadigan fieldlar ro'yxati.
     * @var array
     */
    public array $equalableFields = [];

    /**
     * Modeldagi numeric interval filter qilinadigan fieldlar ro'yxati.
     * @var array
     */
    public array $numericIntervalFields = [];

    /**
     * Modeldagi date interval filter qilinadigan fieldlar ro'yxati.
     * @var array
     */
    public array $dateIntervalFields = [];

    /**
     * Model uchun default order. Agar requestda sort param berilmasa, shu attribute bo'yicha sort qilinadi.
     * @var array
     */
    public array $defaultOrder = [['column' => 'id', 'direction' => 'asc']];

    /**
     * O'chirish, yangilash va bitta ma'lumotni o'qish qaysi field orqali amalga oshirilishi(main operation column)
     * @var string
     */
    protected $id = 'id';

    /**
     * Modeldagi relationlar like filter qilinadigan fieldlar ro'yxati. Translation tabledagi fieldlar bundab mustasno.
     * @var array
     */
    public array $relationLikableFields = [];
    
    /**
     * Qaysi methodlar aftorizatsiya qilinishi kerakligini aytish
     * @var array
     */
    public array $authorizeMethods = [];
}
