<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class FormsCheckbox extends Cell
{
    public $value;
    public $label     = '';
    public $checked   = false;
    public $hidden    = false;
    public $disabled  = false;
}
