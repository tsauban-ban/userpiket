<?php



namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PicketJournal;
use App\Models\ArchivedPicketJournal;
use Carbon\Carbon;

class ArchiveOldPicketJournals extends Command
{
    protected $signature = 'picket:archive';
    protected $description = 'Arsipkan data piket yang lebih dari 2 bulan';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subMonths(2);
        
        
        
        $oldJournals = PicketJournal::where('date', '<', $cutoffDate)->get();
        
        $count = 0;
        
        foreach ($oldJournals as $journal) {
            
        
            ArchivedPicketJournal::create([
                'user_id' => $journal->user_id,
                'date' => $journal->date,
                'activity' => $journal->activity,
                'description' => $journal->description,
                'location' => $journal->location,
                'status' => $journal->status,
                'start_time' => $journal->start_time,
                'end_time' => $journal->end_time,
                'notes' => $journal->notes,
                'before_photo' => $journal->before_photo,
                'after_photo' => $journal->after_photo,
                'archived_at' => now(),
                'created_at' => $journal->created_at,
                'updated_at' => $journal->updated_at,
            ]);
            
            
            
            $journal->delete();
            $count++;
        }
        
        $this->info("Berhasil mengarsipkan {$count} data piket lama.");
    }
}