<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pesanan Saya')]
class MyOrdersPage extends Component{
    use WithPagination;
    public function render(){
        $my_order = Order::where('user_id', auth()->id())->latest()->first()->paginate(5);
        return view('livewire.my-orders-page',[
            'orders' => $my_order,
        ]);
    }
}
