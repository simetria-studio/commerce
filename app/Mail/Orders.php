<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingCustomer;
use App\Models\PaymentOrder;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Orders extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order_number;
    public $send_email;
    public function __construct($order_number,$send_email)
    {
        $this->order_number = $order_number;
        $this->send_email = $send_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch($this->send_email){
            case 'comprador':
                $msg1 = 'Pedido realizado com sucesso ("'.$this->order_number.'").
                Aqui estão os dados que fora informado, caso encontre alguma divergencia pedidos que entre em contato e nos informe.';
            break;
            case 'vendedor':
                $msg1 = 'Pedido realizado com sucesso ("'.$this->order_number.'").
                Aqui estão os dados informados pelo cliente.';
            break;
        }

        $orders = Order::where('order_number', $this->order_number)->with(['orderProducts', 'shippingCustomer', 'paymentOrder'])->first();

        return $this->markdown('emails.orders')->with([
            'order_number' => $this->order_number,
            'orders' => $orders,
            'msg1' => $msg1
        ]);
    }
}
