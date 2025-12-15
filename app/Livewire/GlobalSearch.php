<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';
    public $page = 'documents'; // 'documents' or 'tasks'
    public $showFilters = false;
    
    // Filter states
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';

    public function mount($page = 'documents')
    {
        $this->page = $page;
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->dispatch('global-search-cleared');
    }

    public function clearFilters()
    {
        $this->statusFilter = 'all';
        $this->typeFilter = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->dispatch('global-filters-cleared');
    }

    public function updatedSearch()
    {
        $this->dispatch('global-search-updated', search: $this->search);
    }

    public function updatedStatusFilter()
    {
        $this->dispatch('global-filter-updated', filter: 'status', value: $this->statusFilter);
    }

    public function updatedTypeFilter()
    {
        $this->dispatch('global-filter-updated', filter: 'type', value: $this->typeFilter);
    }

    public function updatedDateFrom()
    {
        $this->dispatch('global-filter-updated', filter: 'dateFrom', value: $this->dateFrom);
    }

    public function updatedDateTo()
    {
        $this->dispatch('global-filter-updated', filter: 'dateTo', value: $this->dateTo);
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
