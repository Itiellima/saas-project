<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\StockMovement;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Oficina de demonstração com itens e OS em todos os status.
 *
 * Login: admin@oficina.test / password
 */
class DemoSeeder extends Seeder
{
    private Tenant $tenant;

    /** @var array<string, Item> */
    private array $items = [];

    public function run(): void
    {
        $this->tenant = Tenant::create([
            'name' => 'Oficina Demo',
            'domain' => 'oficina-demo',
            'email' => 'contato@oficina.test',
            'phone' => '(11) 3333-4444',
            'document' => '12.345.678/0001-90',
        ]);

        User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Administrador Demo',
            'email' => 'admin@oficina.test',
            'password' => 'password',
            'email_verified_at' => now(),
        ])->forceFill(['role' => 'admin'])->save();

        $this->createItems();
        $this->createServiceOrders();
    }

    private function createItems(): void
    {
        $catalog = [
            // Serviços
            ['Troca de óleo (mão de obra)', 'service', 0, 0, 80],
            ['Alinhamento', 'service', 0, 0, 120],
            ['Balanceamento', 'service', 0, 0, 100],
            ['Revisão completa', 'service', 0, 0, 350],
            ['Diagnóstico eletrônico', 'service', 0, 0, 150],

            // Produtos (alguns com estoque baixo para aparecer no dashboard)
            ['Óleo 5W30 1L', 'product', 30, 28, 45],
            ['Filtro de óleo', 'product', 8, 18, 35],
            ['Pastilha de freio dianteira', 'product', 10, 110, 180],
            ['Filtro de ar', 'product', 0, 25, 50],
            ['Vela de ignição', 'product', 20, 15, 30],
        ];

        foreach ($catalog as [$name, $type, $stock, $cost, $price]) {
            $this->items[$name] = Item::create([
                'tenant_id' => $this->tenant->id,
                'name' => $name,
                'type' => $type,
                'stock' => $stock,
                'cost_price' => $cost,
                'sale_price' => $price,
            ]);
        }

        // Item fora de linha: mostra o estado "Inativo"
        $this->items['Lâmpada H4 (fora de linha)'] = Item::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Lâmpada H4 (fora de linha)',
            'type' => 'product',
            'stock' => 2,
            'cost_price' => 15,
            'sale_price' => 25,
            'active' => false,
        ]);
    }

    private function createServiceOrders(): void
    {
        // Aberta, ainda sem itens
        $this->order('MARCOS PEREIRA', 'BRA2E19', 'ONIX', 45200, $this->daysAgo(0), 'BARULHO NA SUSPENSÃO DIANTEIRA');

        // Em andamento há mais tempo
        $order = $this->order('ANA CAROLINA LIMA', 'RIO4F22', 'HB20', 78300, $this->daysAgo(6), 'REVISÃO DOS 80 MIL KM');
        $this->addItem($order, 'Revisão completa');
        $this->addItem($order, 'Óleo 5W30 1L', 4);
        $this->addItem($order, 'Filtro de óleo');
        $this->addItem($order, 'Vela de ignição', 4);

        // Em andamento
        $order = $this->order('JOÃO BATISTA SOUZA', 'SPX7G31', 'GOL', 132000, $this->daysAgo(2), 'FREIO FAZENDO RUÍDO');
        $this->addItem($order, 'Diagnóstico eletrônico');
        $this->addItem($order, 'Pastilha de freio dianteira');

        // Finalizadas neste mês (entram no faturamento)
        $order = $this->order('FERNANDA COSTA', 'MGA1B23', 'COROLLA', 56000, $this->daysAgo(4), 'TROCA DE ÓLEO E FILTRO');
        $this->addItem($order, 'Troca de óleo (mão de obra)');
        $this->addItem($order, 'Óleo 5W30 1L', 4);
        $this->addItem($order, 'Filtro de óleo', 1, 5);
        $this->finish($order, $this->daysAgo(3));

        $order = $this->order('RICARDO ALMEIDA', 'PRB3C45', 'COMPASS', 24100, $this->daysAgo(2), 'ALINHAMENTO E BALANCEAMENTO');
        $this->addItem($order, 'Alinhamento');
        $this->addItem($order, 'Balanceamento', 1, 20);
        $this->finish($order, $this->daysAgo(1));

        // Finalizada no mês passado (não entra no faturamento do mês)
        $lastMonth = now()->subMonthNoOverflow()->startOfMonth()->addDays(9)->setTime(9, 0);
        $order = $this->order('PAULO HENRIQUE DIAS', 'SCD5H67', 'KWID', 61000, $lastMonth, 'TROCA DE PASTILHAS');
        $this->addItem($order, 'Pastilha de freio dianteira');
        $this->addItem($order, 'Diagnóstico eletrônico');
        $this->finish($order, $lastMonth->copy()->addDay());

        // Cancelada: os produtos voltaram ao estoque
        $order = $this->order('LUCIANA MARTINS', 'BAH6J89', 'ARGO', 38700, $this->daysAgo(3), 'ORÇAMENTO DE REVISÃO');
        $this->addItem($order, 'Revisão completa');
        $this->addItem($order, 'Vela de ignição', 4);
        $this->cancel($order, 'Cliente achou o orçamento caro e desistiu do serviço.');

        // Finalizada e reaberta para incluir um item esquecido
        $order = $this->order('CARLOS EDUARDO ROCHA', 'CEA8K12', 'STRADA', 89500, $this->daysAgo(5), 'TROCA DE FILTROS');
        $this->addItem($order, 'Filtro de óleo');
        $this->finish($order, $this->daysAgo(4));
        $order->update([
            'status' => 'in_progress',
            'finished_at' => null,
            'reopened_at' => $this->daysAgo(1),
            'reopen_reason' => 'Esqueci de lançar o filtro de ar na finalização.',
        ]);
    }

    private function order(string $customer, string $plate, string $model, int $km, Carbon $enter, string $description): ServiceOrder
    {
        return ServiceOrder::create([
            'tenant_id' => $this->tenant->id,
            'customer_name' => $customer,
            'customer_phone' => '(11) 9' . random_int(1000, 9999) . '-' . random_int(1000, 9999),
            'vehicle_plate' => $plate,
            'vehicle_model' => $model,
            'vehicle_km' => $km,
            'vehicle_enter' => $enter,
            'description' => $description,
            'status' => 'open',
        ]);
    }

    /**
     * Mesmas regras do ItemForm: copia nome/preço, baixa estoque de produtos,
     * recalcula o total e passa a OS para "em andamento".
     */
    private function addItem(ServiceOrder $order, string $name, int $quantity = 1, float $discount = 0): void
    {
        $item = $this->items[$name];

        ServiceOrderItem::create([
            'tenant_id' => $this->tenant->id,
            'service_order_id' => $order->id,
            'item_id' => $item->id,
            'name' => $item->name,
            'type' => $item->type,
            'quantity' => $quantity,
            'price' => $item->sale_price,
            'discount' => $discount,
            'total' => $item->sale_price * $quantity - $discount,
        ]);

        if ($item->type === 'product') {
            StockMovement::create([
                'tenant_id' => $this->tenant->id,
                'item_id' => $item->id,
                'service_order_id' => $order->id,
                'quantity' => $quantity,
                'type' => 'out',
                'reason' => 'Saída para ordem de serviço #' . $order->id,
            ]);

            $item->decrement('stock', $quantity);
        }

        $order->recalculateTotal();
        $order->markInProgress();
    }

    private function finish(ServiceOrder $order, Carbon $when): void
    {
        $order->update([
            'status' => 'finished',
            'finished_at' => $when,
            'vehicle_leave' => $when,
        ]);
    }

    /**
     * Mesmas regras do cancelamento: devolve os produtos ao estoque.
     */
    private function cancel(ServiceOrder $order, string $reason): void
    {
        foreach ($order->items()->where('type', 'product')->get() as $orderItem) {
            StockMovement::create([
                'tenant_id' => $this->tenant->id,
                'item_id' => $orderItem->item_id,
                'service_order_id' => $order->id,
                'quantity' => $orderItem->quantity,
                'type' => 'in',
                'reason' => 'Cancelamento da ordem de serviço #' . $order->id,
            ]);

            Item::find($orderItem->item_id)->increment('stock', $orderItem->quantity);
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);
    }

    /**
     * Dias atrás, sem sair do mês atual (para as OS "deste mês" valerem até no dia 1º).
     */
    private function daysAgo(int $days): Carbon
    {
        return now()->subDays(min($days, now()->day - 1))->setTime(8, 30);
    }
}
