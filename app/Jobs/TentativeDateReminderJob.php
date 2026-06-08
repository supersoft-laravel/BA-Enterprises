<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\VehicleCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class TentativeDateReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = Carbon::today();

        $cases = VehicleCase::where('status', 'open')
            ->whereNotNull('tentative_return_date')
            ->get();

        if ($cases->isEmpty()) {
            return;
        }

        $adminUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super-admin']);
        })->get();

        foreach ($cases as $case) {

            $dueDate = Carbon::parse($case->tentative_return_date);

            $daysLeft = $today->diffInDays($dueDate, false);

            if (!in_array($daysLeft, [2, 1, 0]) && $daysLeft > 0) {
                continue;
            }

            if ($daysLeft == 2) {
                $message = "Reminder: Case #{$case->case_no} is due in 2 days ({$case->tentative_return_date})";
            } elseif ($daysLeft == 1) {
                $message = "Urgent: Case #{$case->case_no} is due TOMORROW ({$case->tentative_return_date})";
            } elseif ($daysLeft == 0) {
                $message = "⚠️ ALERT: Case #{$case->case_no} is DUE TODAY ({$case->tentative_return_date})";
            } else {
                $message = "⚠️ OVERDUE: Case #{$case->case_no} is past due ({$case->tentative_return_date})";
            }

            app('notificationService')->notifyUsers(
                $adminUsers,
                $message,
                'cases',
                $case->id
            );
        }
    }
}
