<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Anime;

class Search extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedStudio = null;
    public $selectedYear = null;
    public $selectedTag = null;
    public $selectedStatus = null;

    public function submitSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function getFilteredResultsProperty()
    {
        $query = Anime::query();

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->selectedStudio) {
            $query->where('studio', $this->selectedStudio);
        }

        if ($this->selectedYear) {
            $query->whereRaw('YEAR(aired_at) = ?', [$this->selectedYear]);
        }

        if ($this->selectedTag) {
            $query->whereHas('tags', function ($q) {
                $q->where('name_mn', $this->selectedTag);
            });
        }

        if ($this->selectedStatus) {
            $query->where('category_id', $this->selectedStatus);
        }

        return $query->orderBy('created_at', 'desc')->paginate(24);
    }


    public function render()
    {
        $studios = Anime::select('studio')->whereNotNull('studio')->distinct()->pluck('studio');
        $years = Anime::selectRaw('DISTINCT YEAR(aired_at) as year')
            ->whereNotNull('aired_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        $tags = Anime::with('tags')->get()->pluck('tags')->flatten()->pluck('name_mn')->unique();
        $statuses = ['TV Series', 'OVA', 'ONA', 'Movie'];

        return view('livewire.search', [
            'animes' => $this->filteredResults,
            'studios' => $studios,
            'years' => $years,
            'tags' => $tags,
            'statuses' => $statuses,
        ]);
    }

}
