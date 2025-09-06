<?php

namespace App\Livewire\Pages;

use App\Livewire\Traits\CategoryFilterTrait;
use App\Models\Category;
use App\Models\Food;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FavoritePage extends Component
{
    use CategoryFilterTrait;

    public $categories;
    public $selectedCategories = [];
    public $items;
    public $title = "Favorite";

    public function mount(Food $foods)
    {
        $this->categories = Category::all();
        $this->items = $foods->getFavoriteFood();
    }

    #[Layout('components.layouts.page')]

    public function render()
    {

        $filteredProducts = $this->getFilteredItems();
        return view('livewire.pages.favorite-page', [
            'filteredProducts' => $filteredProducts,
        ]);
    }
}
