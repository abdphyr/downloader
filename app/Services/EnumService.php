<?php

namespace App\Services;

use App\Enums\EducationDegreeEnum;
use App\Enums\SubjectStatusEnum;

class EnumService extends BaseService
{
    public function makeAction($enum)
    {
        $this->setResponse(data: $enum::toArray());
        return $this->return();
    }

    public function subjectStatus()
    {
        return $this->makeAction(SubjectStatusEnum::class);
    }
    
    public function educationDegree()
    {
        return $this->makeAction(EducationDegreeEnum::class);
    }
}
