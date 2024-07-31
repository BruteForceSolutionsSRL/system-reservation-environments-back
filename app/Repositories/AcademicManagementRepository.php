<?php
namespace App\Repositories;

use App\Models\AcademicManagement;
use Carbon\Carbon; 

class AcademicManagementRepository 
{
    protected $model; 

    public function __construct()
    {
        $this->model = AcademicManagement::class;
    }

    public function list(): array 
    {
        return $this->model::all()->map(
            function ($academicManagement) 
            {
                return $this->formatOutput($academicManagement);
            }
        )->toArray();
    }

    public function getAcademicManagement(int $academicManagementId): array 
    {
        return $this->formatOutput($this->model::find($academicManagementId));
    }

    public function store(array $data): array 
    {
        $academicManagement = new $this->model(); 
        $academicManagement->name = $data['name']; 
        $academicManagement->initial_date = $data['date_start']; 
        $academicManagement->end_date = $data['date_end'];
        $academicManagement->save(); 
    }

    public function update(array $data, int $academicManagementId): array 
    {
        $academicManagement = $this->model::find($academicManagementId); 
        $academicManagement->initial_date = $data['date_start']; 
        $academicManagement->end_date = $data['date_end'];
        $academicManagement->save();         
    }

    public function formatOutput($academicManagement) 
    {
        if ($academicManagement === null) return [];
        return [
            'academic_management_id' => $academicManagement->id,
            'name' => $academicManagement->name,
            'initial_date' => $academicManagement->initial_date,
            'end_date' => $academicManagement->end_date,
        ];
    }
}