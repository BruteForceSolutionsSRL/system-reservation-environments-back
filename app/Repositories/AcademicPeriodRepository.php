<?php
namespace App\Repositories;

use App\Models\AcademicPeriod;
use Illuminate\Support\Carbon;

class AcademicPeriodRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = AcademicPeriod::class;
    }

    public function save(array $data): array
    {
        $academicPeriod = new AcademicPeriod();

        $academicPeriod->name = $data['academic_period_name'];
        $academicPeriod->initial_date = $data['initial_date'];
        $academicPeriod->end_date = $data['end_date'];
        $academicPeriod->activated = 1;

        $academicPeriod->save();

        return $this->formatOutput($academicPeriod);
    }

    public function getAcademicPeriodById(int $id): array
    {
        $academicPeriod = $this->model::where('id', '=', $id)->get();

        if ($academicPeriod == null) return [];

        return $this->formatOutput($academicPeriod);
    }

    public function getAllAcademicPeriods(): array
    {
        return $this->model::all()->map(
            function ($academicPeriod)
            {
                return $this->formatOutput($academicPeriod);
            }
        )->toArray();
    }

    public function getActiveAcademicPeriod(): array
    {
        $academicPeriod = $this->model::where('activated', '=', 1)
            ->get()->first();

        if (!$academicPeriod)
            return [];

        return $this->formatOutput($academicPeriod);
    }

    /**
     * 1 -> succesfully deactivated
     * 2 -> this academic period has already been deactivated
     * 3 -> this academic period is still active
     * @return int
     */
    public function deactivateActiveAcademicPeriod(): int
    {
        $academicPeriod = $this->model::where('activated', '=', 1)
            ->get()->first();

        if ($academicPeriod->activated == 0)
            return 2;

        $now = Carbon::now()->setTimeZone('America/New_York');
        $date = $now->format('Y-m-d');

        if ($academicPeriod['end_date'] > $date)
            return 3;

        $academicPeriod->activated = 0;
        $academicPeriod->save();

        return 1;
    }

    private function formatOutput($academicPeriod): array
    {
        return [
            'academic_period_id' => $academicPeriod->id,
            'academic_period_name' => $academicPeriod->name,
            'initial_date' => $academicPeriod->initial_date,
            'end_date' => $academicPeriod->end_date,
            'activated' => $academicPeriod->activated
        ];
    }
}
