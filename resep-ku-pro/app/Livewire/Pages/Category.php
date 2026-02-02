<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Category extends Component
{
   use WithPagination;

    public $name, $selected_id, $search = '';
    public $is_editing = false;

    protected $rules = [
        'name' => 'required|min:2|max:50',
    ];

    public function updatingSearch() { $this->resetPage(); }

    private function writeLog($action, $details)
    {
        DB::table('activity_logs')->insert([
            'id' => (string) Str::uuid(),
            'org_id' => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action' => $action,
            'details' => $details,
            'created_at' => now(),
        ]);
    }

    public function save()
    {
        $this->validate();

        if ($this->is_editing) {
            $cat = \App\Models\Category::findOrFail($this->selected_id);
            $cat->update(['name' => $this->name]);
            $this->writeLog('Update Category', "Renamed category to: {$this->name}");
        } else {
            \App\Models\Category::create([
                'name' => $this->name,
                'org_id' => auth()->user()->org_id
            ]);
            $this->writeLog('Create Category', "Created new category: {$this->name}");
        }

        $this->resetForm();
        session()->flash('success', 'Category saved successfully!');
    }

    public function edit($id)
    {
        $cat = \App\Models\Category::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $cat->name;
        $this->is_editing = true;
    }

    public function delete($id)
    {
        $cat = \App\Models\Category::findOrFail($id);
        $name = $cat->name;
        $cat->delete();

        $this->writeLog('Delete Category', "Removed category: {$name}");
        session()->flash('success', 'Category deleted.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'is_editing', 'selected_id']);
    }

    public function render()
    {
        $query = \App\Models\Category::where('org_id', auth()->user()->org_id);

        if ($this->search) {
            $query->where('name', 'REGEXP', $this->search);
        }

        return view('livewire.pages.category', [
            'categories' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
