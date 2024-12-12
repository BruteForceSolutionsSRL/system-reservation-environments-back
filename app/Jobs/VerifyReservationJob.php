<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\{
    ReservationStatusRepository as ReservationStatuses,
};
use App\Service\ServiceImplementation\ReservationServiceImpl;


class VerifyReservationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservationId; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reservationId)
    {
        $this->reservationId = $reservationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reservationService = new ReservationServiceImpl();
        $reservation = $reservationService->getReservation($this->reservationId);
        if ($reservation['verified'] == 0) {
            $reservationService->cancelAndRejectReservations(
                [$reservation],
                'Su reserva fue cancelada/rechazada porque no se realizo una segunda verificacion enviada a su correo, si cree que se trata de un error, contacte con el administrador'
            );
        }
    }
}
