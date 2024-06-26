<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use App\Models\Category;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Product - IwangSport')]
class ProductsPage extends Component{

    use  LivewireAlert;
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 100000;

    #[Url]
    public $sort = 'latest';

    // Method to add product to cart
    // public function addToCart($products_id)
    // {
    //     $total_count = CartManagement::addItemToCart($products_id);
    //     $this->dispatch('update-cart-count', ['total_count' => $total_count])->to(Navbar::class);
    // }
    public function addToCart($products_id){
        $total_count = CartManagement::addItemToCart($products_id);
        $this->dispatch('update-cart-count', $total_count)->to(Navbar::class);
        
        $this->alert('success', 'Product Ditambah Ke Keranjang', [
            'position' => 'top',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        }

        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page', [
            'product' => $productQuery->paginate(6),
            'category' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
