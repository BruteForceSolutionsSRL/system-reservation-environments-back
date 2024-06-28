<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Service\ServiceImplementation\{
    ClassroomServiceImpl, 
    ReservationServiceImpl
};

use App\Repositories\{
    ReservationRepository, 
    ReservationStatusRepository as ReservationStatus
};

class ReassignerReservationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dates; 
    private $timeSlots; 
    private $blocks;  
    private $reservationController;
    private $classroomService; 
    private $reservationService; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dates, $timeSlots, $blocks)
    {
        $this->dates = $dates; 
        $this->timeSlots = $timeSlots; 
        $this->blocks = $blocks; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->reservationService = new ReservationServiceImpl(); 
        $this->classroomService = new ClassroomServiceImpl();

        $this->reservationController = new ReservationRepository();
        // aqui falta el algoritmo para tratar de reasignar <:v
    }
}
