<?php

namespace App\Services;

use App\Services\BaseService;
use App\Models\User;

class UserService extends BaseService
{
    public function __construct(User $serviceModel)
    {
        $this->model = $serviceModel;

        $this->likableFields = [
            'firstname',
            'lastname',
            'username'
        ];

        $this->equalableFields = [
            'id',
        ];

        $this->dateIntervalFields = [
            'created_at'
        ];

        parent::__construct();
    }
}
