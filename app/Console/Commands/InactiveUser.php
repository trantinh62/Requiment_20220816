<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\NotifyInactiveUser;

class InactiveUser extends Command
{

    protected $signature = 'email:inactiveUsers';

    protected $description = 'Send email to Inactive Users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $limit = Carbon::now()->subDay(7);
        $inactive_user = User::where('created_at', '<', $limit)->where('password', null)->get();
        foreach ($inactive_user as $user) {
            $user->delete();
        }
    }
}

