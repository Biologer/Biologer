<?php

namespace Tests;

use App\Importing\BaseImport;
use Illuminate\Support\Facades\Validator;

class FakeImporter extends BaseImport
{
    public static function columns($user = null)
    {
        return collect([
            ['value' => 'a', 'label' => 'A', 'required' => false],
            ['value' => 'b', 'label' => 'B', 'required' => true],
            ['value' => 'c', 'label' => 'C', 'required' => false],
        ]);
    }

    protected function makeValidator(array $data)
    {
        return Validator::make($data, [
            'a' => 'nullable|numeric|min:2',
            'b' => 'required|string',
            'c' => 'nullable|string',
        ]);
    }

    protected function storeSingleItem(array $item)
    {
        //
    }
}
