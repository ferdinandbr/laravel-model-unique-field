<?php

namespace Ferdinandbr\LaravelModelUniqueField\Models;

use Illuminate\Database\Eloquent\Model;
use Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField;

class TestModel extends Model
{
    use UniqueField;

    protected $table = 'test_model';

    protected $dynamicField = 'name';

    protected $fillable = [
        'name'
    ];
}
