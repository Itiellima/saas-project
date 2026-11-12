<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OS #{{ $serviceOrder->id }} - {{ $serviceOrder->tenant->name }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            background-color: #f1f3f5;
            color: #212529;
            font-size: 13px;
        }

        .sheet {
            background-color: #fff;
            max-width: 210mm;
            margin: 24px auto;
            padding: 12mm;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .box {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 10px;
            height: 100%;
        }

        .table th,
        .table td {
            padding: 4px 6px;
        }

        .table tr {
            break-inside: avoid;
        }

        .signature {
            border-top: 1px solid #212529;
            padding-top: 4px;
            text-align: center;
        }

        .cancelled-stamp {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 96px;
            font-weight: 800;
            color: rgba(220, 53, 69, 0.15);
            border: 8px solid rgba(220, 53, 69, 0.15);
            padding: 0 24px;
            pointer-events: none;
            white-space: nowrap;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .sheet {
                margin: 0;
                padding: 0;
                max-width: none;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

</head>


<body>

    {{-- AÇÕES (não saem no papel) --}}
    <div class="no-print text-center mt-3">
        <button type="button" class="btn btn-dark" onclick="window.print()">
            Imprimir
        </button>
        <a href="{{ route('service-orders.show', $serviceOrder->id) }}" class="btn btn-outline-secondary">
            Voltar para a OS
        </a>
    </div>


    <div class="sheet">

        @if ($serviceOrder->status === 'cancelled')
            <div class="cancelled-stamp">CANCELADA</div>
        @endif


        {{-- CABEÇALHO --}}
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">

            <div>
                <h4 class="fw-bold mb-1">{{ $serviceOrder->tenant->name }}</h4>

                <div class="text-secondary">
                    {{ collect([
                        $serviceOrder->tenant->phone,
                        $serviceOrder->tenant->email,
                        $serviceOrder->tenant->document ? 'CNPJ/CPF ' . $serviceOrder->tenant->document : null,
                    ])->filter()->implode(' · ') }}
                </div>
            </div>

            <div class="text-end">
                <div class="section-title mb-0">Ordem de serviço</div>
                <h3 class="fw-bold mb-1">Nº {{ $serviceOrder->id }}</h3>
                <div>Status: <strong>{{ $serviceOrder->statusConfig()['label'] }}</strong></div>
            </div>

        </div>


        @if ($serviceOrder->status === 'cancelled')
            <div class="border border-danger text-danger rounded p-2 mb-3">
                <strong>OS CANCELADA{{ $serviceOrder->cancelled_at ? ' em ' . $serviceOrder->cancelled_at->format('d/m/Y H:i') : '' }}.</strong>
                Este documento não tem validade como ordem de serviço.
                @if ($serviceOrder->cancel_reason)
                    <div>Motivo: {{ $serviceOrder->cancel_reason }}</div>
                @endif
            </div>
        @endif


        {{-- CLIENTE / VEÍCULO --}}
        <div class="row g-3 mb-3">

            <div class="col-6">
                <div class="box">
                    <div class="section-title">Cliente</div>
                    <div class="fw-semibold">{{ $serviceOrder->customer_name }}</div>
                    <div>Telefone: {{ $serviceOrder->customer_phone ?: 'Não informado' }}</div>
                </div>
            </div>

            <div class="col-6">
                <div class="box">
                    <div class="section-title">Veículo</div>
                    <div class="fw-semibold">{{ $serviceOrder->vehicle_plate ?: 'Placa não informada' }}</div>
                    <div>Modelo: {{ $serviceOrder->vehicle_model ?: 'Não informado' }}</div>
                    <div>Quilometragem:
                        {{ $serviceOrder->vehicle_km ? number_format($serviceOrder->vehicle_km, 0, ',', '.') . ' km' : 'Não informada' }}
                    </div>
                </div>
            </div>

        </div>


        {{-- DATAS --}}
        <div class="box mb-3 d-flex gap-5">
            <div>
                <span class="section-title d-inline">Entrada:</span>
                {{ $serviceOrder->vehicle_enter?->format('d/m/Y H:i') ?? 'Não informada' }}
            </div>
            <div>
                <span class="section-title d-inline">Saída:</span>
                {{ $serviceOrder->vehicle_leave?->format('d/m/Y H:i') ?? 'Não informada' }}
            </div>
        </div>


        {{-- DESCRIÇÃO --}}
        <div class="box mb-3">
            <div class="section-title">Descrição do serviço</div>
            <div style="white-space: pre-line;">{{ $serviceOrder->description ?: 'Nenhuma descrição informada.' }}</div>
        </div>


        {{-- ITENS --}}
        <div class="section-title">Serviços e produtos</div>

        <table class="table table-bordered table-sm mb-3">

            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Tipo</th>
                    <th class="text-center">Qtd.</th>
                    <th class="text-end">Unitário</th>
                    <th class="text-end">Desconto</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($serviceOrder->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->type === 'product' ? 'Produto' : 'Serviço' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="text-end">
                            {{ $item->discount > 0 ? '- R$ ' . number_format($item->discount, 2, ',', '.') : '—' }}
                        </td>
                        <td class="text-end">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary">Nenhum item adicionado.</td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">TOTAL</th>
                    <th class="text-end fs-6">R$ {{ number_format($serviceOrder->total, 2, ',', '.') }}</th>
                </tr>
            </tfoot>

        </table>


        {{-- ASSINATURAS --}}
        <div class="row g-5 mt-4 pt-4">
            <div class="col-6">
                <div class="signature">Assinatura do cliente</div>
            </div>
            <div class="col-6">
                <div class="signature">Responsável - {{ $serviceOrder->tenant->name }}</div>
            </div>
        </div>


        <div class="text-end text-secondary mt-4" style="font-size: 11px;">
            Emitida em {{ now()->format('d/m/Y H:i') }}
        </div>

    </div>


    <script>
        // Abre a janela de impressão assim que a página carrega
        window.addEventListener('load', () => window.print());
    </script>

</body>

</html>
