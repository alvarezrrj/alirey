<?php

namespace App\Forms\Components;

use App\Models\Code;
use Filament\Forms\Components\Field;

class CodeSelect extends Field
{
    public $codes = Code::all();

    protected string $view = 'forms.components.code-select';
}
