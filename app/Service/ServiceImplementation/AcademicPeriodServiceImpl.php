<?php
namespace App\Service\ServiceImplementation;

use App\Repositories\{
    AcademicPeriodRepository
};

use App\Service\AcademicPeriodService;

class AcademicPeriodServiceImpl implements AcademicPeriodService
{
    private $academicPeriodRepository;

    public function __construct()
    {
        $this->academicPeriodRepository = new AcademicPeriodRepository();
    }

    public function store(array $data): string
    {
        $this->academicPeriodRepository->save($data);

        return 'Se registro el periodo academico '.$data['academic_period_name'];
    }

    public function getActiveAcademicPeriod(): array
    {
        return $this->academicPeriodRepository->getActiveAcademicPeriod();
    }

    public function deactivateActiveAcademicPeriod(): int
    {
        return $this->academicPeriodRepository->deactivateActiveAcademicPeriod();
    }
}

