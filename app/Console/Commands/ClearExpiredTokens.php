<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class ClearExpiredTokens extends Command
{
    protected $signature = 'tokens:clear-expired';
    protected $description = 'Xóa các token đã hết hạn';
    //php artisan tokens:clear-expired
    public function handle()
    {
        // Xóa tất cả các token đã hết hạn
        $expiredTokens = PersonalAccessToken::where('expires_at', '<', now())->delete();

        $this->info("Đã xóa {$expiredTokens} token đã hết hạn.");
    }
}
