<?php

namespace App\Livewire\Pages;

use App\Livewire\Traits\CategoryFilterTrait;
use App\Models\Category;
use App\Models\Food;
use Livewire\Attributes\Layout;
use Livewire\Component;


class AllFoodPage extends Component
{
    use CategoryFilterTrait;

    public $categories;
    public $selectedCategories = [];
    public $items;
    public $title = "All Foods";

    public function mount(Food $foods)
    {
        $this->categories = Category::all();
        $this->items = $foods->getAllFoods();
    }

    #[Layout('components.layouts.page')]

    public function render()
    {
        $filteredProducts = $this->getFilteredItems();
        return view('livewire.pages.all-food-page', [
            'filteredProducts' => $filteredProducts,
        ]);
    }
}
