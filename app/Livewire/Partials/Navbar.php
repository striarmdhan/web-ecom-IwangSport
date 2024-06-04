<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component{

    public $total_count = 0;

    public function mount(){
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }
    #[On('update-cart-count')]
    public function updateCartCount($total_count)
    {
        // Logging untuk debugging
        error_log('updateCartCount called with total_count: ' . json_encode($total_count));

        // Pastikan total_count adalah integer
        if (is_array($total_count)) {
            throw new \Exception('total_count should not be an array: ' . json_encode($total_count));
        }

        $this->total_count = (int) $total_count;
    }

    public function render(){
        return view('livewire.partials.navbar');
    }
}
