<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';

    // Reset pagination saat search berubah
    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        $query = DB::table('activity_logs')
            ->where('org_id', auth()->user()->org_id);

        if ($this->search) {
            /** * SEARCH DENGAN REGEX
             * Menggunakan REGEXP (MySQL) atau ~ (PostgreSQL/Supabase)
             */
            $query->where(function($q) {
                $q->where('details', 'REGEXP', $this->search)
                  ->orWhere('action', 'REGEXP', $this->search)
                  ->orWhere('user_email', 'REGEXP', $this->search);
            });
        }

        return view('livewire.pages.activity-logs', [
            'logs' => $query->orderBy('created_at', 'desc')->paginate(15)
        ])->layout('layouts.app');
    }
}
