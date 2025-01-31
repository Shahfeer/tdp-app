<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;


class UsersExport implements FromCollection
{
    use Exportable, SerializesModels;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function collection(): Collection
    {
        return $this->data;
    }
}   





