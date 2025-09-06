<?php

namespace App\Livewire\Pages;

use App\Livewire\Traits\CategoryFilterTrait;
use App\Models\Category;
use App\Models\Food;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PromoPage extends Component
{
    use CategoryFilterTrait;

    public $categories;
    public $selectedCategories = [];
    public $items;
    public $title = "Promo";

    public function mount(Food $food)
    {
        $this->categories = Category::all();
        $this->items = $food->getPromo();
    }

    #[Layout('components.layouts.page')]

    public function render()
    {
        $filteredProducts = $this->getFilteredItems();
        return view('livewire.pages.promo-page', [
            'filteredProducts' => $filteredProducts,
        ]);
    }
}
