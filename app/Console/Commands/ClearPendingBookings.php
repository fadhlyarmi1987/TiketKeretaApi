<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class ClearPendingBookings extends Command
{
    protected $signature = 'bookings:clear-pending';
    protected $description = 'Hapus booking PENDING yang lebih dari 10 menit';

    public function handle()
    {
        $expiredAt = Carbon::now()->subMinutes(10);

        $count = Booking::where('status', 'PENDING')
            ->where('created_at', '<', $expiredAt)
            ->delete();

        $this->info("{$count} booking PENDING dihapus.");
    }
}
