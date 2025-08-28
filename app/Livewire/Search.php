<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Episode;
use App\Models\Tag;
use App\Models\Category;

class Search extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $selectedYear = null;
    public $selectedTag = null;
    public $selectedStatus = null;
    public $selectedStudio = null;
    public $sortBy = 'published_at';
    public $sortOrder = 'desc';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'selectedYear' => ['except' => ''],
        'selectedTag' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'selectedStudio' => ['except' => ''],
        'sortBy' => ['except' => 'published_at'],
        'sortOrder' => ['except' => 'desc'],
    ];

    public function submitSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function updatedSelectedTag()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectedStudio()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortOrder = 'desc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['searchTerm', 'selectedYear', 'selectedTag', 'selectedStatus', 'selectedStudio']);
        $this->resetPage();
    }

    public function getFilteredResultsProperty()
    {
        $query = Episode::query();

        // Only show published and public episodes
        $query->where('status', 'published')
              ->where('visibility', 'public');

        // Search term filter
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('title_english', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('title_japanese', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('synopsis', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('anime', function($animeQuery) {
                      $animeQuery->where('title', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('title_english', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('title_japanese', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        // Year filter
        if ($this->selectedYear) {
            $query->whereRaw('YEAR(published_at) = ?', [$this->selectedYear]);
        }

        // Tag filter
        if ($this->selectedTag) {
            $query->whereHas('tags', function ($q) {
                $q->where('name_mn', $this->selectedTag);
            });
        }

        // Status filter (using anime category_id)
        if ($this->selectedStatus) {
            $query->whereHas('anime', function($q) {
                $q->where('category_id', $this->selectedStatus);
            });
        }

        // Studio filter
        if ($this->selectedStudio) {
            $query->whereHas('anime', function($q) {
                $q->where('studio', $this->selectedStudio);
            });
        }

        // Sorting
        $allowedSortFields = ['published_at', 'created_at', 'title', 'episode_number', 'view_count', 'average_rating'];
        if (!in_array($this->sortBy, $allowedSortFields)) {
            $this->sortBy = 'published_at';
        }

        $query->orderBy($this->sortBy, $this->sortOrder);

        return $query->with(['anime', 'anime.category', 'tags'])->paginate(24);
    }

    public function render()
    {
        // Get years from episodes
        $years = Episode::selectRaw('DISTINCT YEAR(published_at) as year')
            ->whereNotNull('published_at')
            ->whereRaw('YEAR(published_at) IS NOT NULL')
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get tags from episodes
        $tags = Tag::select('name_mn')
            ->whereNotNull('name_mn')
            ->where('name_mn', '!=', '')
            ->whereHas('episodes', function($q) {
                $q->where('status', 'published')
                  ->where('visibility', 'public');
            })
            ->distinct()
            ->orderBy('name_mn')
            ->pluck('name_mn');

        // Get categories for status filter
        $categories = Category::select('id', 'name')
            ->whereHas('animes.episodes', function($q) {
                $q->where('status', 'published')
                  ->where('visibility', 'public');
            })
            ->orderBy('name')
            ->get();

        // Get studios for studio filter
        $studios = Episode::select('anime_id')
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereHas('anime', function($q) {
                $q->whereNotNull('studio')
                  ->where('studio', '!=', '');
            })
            ->with('anime:id,studio')
            ->get()
            ->pluck('anime.studio')
            ->unique()
            ->filter()
            ->sort()
            ->values();

        return view('livewire.search', [
            'episodes' => $this->filteredResults,
            'years' => $years,
            'tags' => $tags,
            'categories' => $categories,
            'studios' => $studios,
        ]);
    }
}
