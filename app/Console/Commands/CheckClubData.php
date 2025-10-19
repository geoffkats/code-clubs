<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Club;

class CheckClubData extends Command
{
    protected $signature = 'check:club-data';
    protected $description = 'Check club data and relationships';

    public function handle()
    {
        $this->info('Checking club data...');
        
        $clubs = Club::with(['sessions', 'students'])->get();
        
        foreach ($clubs as $club) {
            $this->line("Club {$club->id}: {$club->club_name}");
            $this->line("  - Sessions: {$club->sessions->count()}");
            $this->line("  - Students: {$club->students->count()}");
            
            if ($club->sessions->count() > 0) {
                foreach ($club->sessions as $session) {
                    $this->line("    Session {$session->id}: Week {$session->session_week_number} - {$session->session_date}");
                }
            }
            $this->line('');
        }
    }
}