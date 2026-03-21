<?php



namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PicketJournalEvent;
use App\Listeners\SendPicketJournalNotification;

class EventServiceProvider extends ServiceProvider
{
    

    protected $listen = [
        
    
        PicketJournalEvent::class => [
            SendPicketJournalNotification::class,
        ],
    ];

    
    
    public function boot(): void
    {
        parent::boot();
    }

    
    
    public function shouldDiscoverEvents(): bool
    {
        return true; 
        
    }
}