<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('migrate:fresh --seed --force')->dailyAt('00:00');
