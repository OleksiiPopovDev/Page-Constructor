<?php

namespace App\Containers\Constructor\Language\Tasks;

interface DeleteLanguageTaskInterface
{
    public function run(int $id): void;
}
