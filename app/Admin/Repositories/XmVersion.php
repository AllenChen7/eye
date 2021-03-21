<?php

namespace App\Admin\Repositories;

use App\Models\XmVersion as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class XmVersion extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
