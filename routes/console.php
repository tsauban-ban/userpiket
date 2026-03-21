<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ArchiveOldPicketJournals;

Schedule::command('picket:archive')->dailyAt('01:00');

Schedule::command('picket:archive')->weekly();
Schedule::command('picket:archive')->monthly();
Schedule::command('picket:archive')->everySixHours();

Schedule::command('picket:archive')->everyMinute();