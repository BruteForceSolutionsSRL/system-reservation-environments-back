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
    ReservationServiceImpl,
    BlockServiceImpl
};

use App\Repositories\{
    ReservationRepository, 
    ReservationStatusRepository as ReservationStatus,
    PersonRepository
};

class ReassignerReservationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reservations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reservationService = new ReservationServiceImpl(); 
        $classroomService = new ClassroomServiceImpl();
        $blockService = new BlockServiceImpl();

        $reservationRepository = new ReservationRepository();
        $reservationsToRejectAndCancel = [];
        foreach ($this->reservations as $reservation) {            
            $blocks = $blockService->getAllBlocks(); 
            $classrooms = [];
            foreach ($blocks as $block) {
                $possibleAssignation = $classroomService->suggestClassrooms(
                    [
                        'block_id' => $block['block_id'],
                        
                    ]
                );
                if (!is_string($possibleAssignation[0])) {
                    $classrooms = $possibleAssignation;
                    break; 
                }
            }
            if (!empty($classrooms)) {
                $reservationRepository
                    ->detachReservationsClassrooms($reservation['reservation_id']);
                $reservation = $reservationService->assignClassrooms(
                    $reservation['reservation_id'],
                    array_map(
                        function ($classroom) {
                            return $classroom['classroom_id'];
                        },
                        $classrooms
                     )
                );  
            } else {
                array_push($reservationsToRejectAndCancel, $reservation);
            }
        }
        $reservationService->cancelAndRejectReservations($reservationsToRejectAndCancel);
    }
}
