<?php

namespace App\Models;

use App\Partials\File\File;
use App\Partials\File\HasFile;
use App\Partials\File\WithFile;
use App\Traits\HasTranslation;

class Subject extends BaseModel implements WithFile
{
    const IMAGE = 10;
    const DOCUMENT = 20;

    use HasTranslation, HasFile;

    public $translationClass = SubjectTranslation::class;

    protected $guarded = [];

    protected $fillable = [
        // TRANSLATIONS
        // 'title',
        // 'description',

        // string
        'code',
        'info',

        // enums
        'status',
        'degree',
        
        // keys
        'type_id',
        'lang_id',
        'category_id',
        'user_id',
        'author_id',

        // counts
        'downloads',
        'views',
        'reviews',
        'sumreviews'
    ];

    public function fileTypes()
    {
        return [
            File::FILE => 'file',
            File::FILES => 'files',
            static::DOCUMENT => 'document',
            static::IMAGE => 'image'
        ];
    }

    public function document()
    {
        return $this->file()->where('type', self::DOCUMENT);
    }

    public function image()
    {
        return $this->file()->where('type', self::IMAGE);
    }

    public function type()
    {
        return $this->belongsTo(SubjectType::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
