{{--
    Balões de notificação (toasts do Bootstrap).

    - Do servidor: redirect()->with('success' | 'error' | 'warning', 'mensagem')
    - De um componente Livewire: $this->dispatch('toast', type: 'success', message: 'mensagem')
    - Do JavaScript: window.showToast('success', 'mensagem')
--}}

@php
    $toastTypes = [
        'success' => ['icon' => 'bi-check-circle-fill', 'title' => 'Tudo certo', 'delay' => 5000],
        'error' => ['icon' => 'bi-x-circle-fill', 'title' => 'Algo deu errado', 'delay' => 8000],
        'warning' => ['icon' => 'bi-exclamation-triangle-fill', 'title' => 'Atenção', 'delay' => 8000],
    ];

    $flashToasts = collect(['success', 'error', 'warning'])
        ->filter(fn ($type) => session()->has($type))
        ->map(fn ($type) => ['type' => $type, 'message' => session($type)]);
@endphp


<style>
        .toast-stack {
            z-index: 1090;
            top: 0;
            left: 0;
            right: 0;
        }

        /* Computador: canto superior direito, abaixo da barra do topo */
        @media (min-width: 768px) {
            .toast-stack {
                top: 72px;
                left: auto;
                width: 380px;
            }
        }

        .app-toast {
            width: 100%;
            border: 0;
            border-left: 5px solid var(--toast-color);
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .15);
        }

        .app-toast .toast-icon {
            color: var(--toast-color);
        }

        .app-toast-success { --toast-color: #198754; }
        .app-toast-error { --toast-color: #dc3545; }
        .app-toast-warning { --toast-color: #ffc107; }
</style>


<div class="toast-stack position-fixed p-3 d-flex flex-column gap-2" id="toast-stack" aria-live="polite" aria-atomic="true">

    @foreach ($flashToasts as $toast)
        @php
            $config = $toastTypes[$toast['type']];
        @endphp

        <div class="toast app-toast app-toast-{{ $toast['type'] }}" role="{{ $toast['type'] === 'success' ? 'status' : 'alert' }}"
            data-bs-delay="{{ $config['delay'] }}">
            <div class="toast-body d-flex align-items-start gap-2">
                <i class="bi {{ $config['icon'] }} toast-icon fs-5"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $config['title'] }}</div>
                    <div class="text-secondary small">{{ $toast['message'] }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    @endforeach


    {{-- Erros de validação: os campos já ficam marcados; o balão só avisa (e mostra os erros sem campo na tela) --}}
    @if ($errors->any())
        <div class="toast app-toast app-toast-error" role="alert" data-bs-delay="{{ $toastTypes['error']['delay'] }}"
            data-validation-toast>
            <div class="toast-body d-flex align-items-start gap-2">
                <i class="bi {{ $toastTypes['error']['icon'] }} toast-icon fs-5"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">Não foi possível salvar</div>
                    <div class="text-secondary small">Confira os campos destacados.</div>

                    <ul class="text-secondary small mb-0 ps-3 mt-1 d-none" data-unplaced-errors>
                        @foreach ($errors->getMessages() as $field => $messages)
                            <li data-field="{{ $field }}" class="d-none">{{ $messages[0] }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    @endif

</div>


{{-- Modelo usado pelos balões criados no navegador (Livewire / JavaScript) --}}
<template id="toast-template">
    <div class="toast app-toast" role="status">
        <div class="toast-body d-flex align-items-start gap-2">
            <i class="bi toast-icon fs-5"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold" data-toast-title></div>
                <div class="text-secondary small" data-toast-message></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>
    </div>
</template>


@push('scripts')
    <script>
        (() => {
            const types = @json($toastTypes);
            const stack = document.getElementById('toast-stack');

            const show = (element) => {
                // Remove do HTML quando some, para não acumular
                element.addEventListener('hidden.bs.toast', () => element.remove());
                bootstrap.Toast.getOrCreateInstance(element).show();
            };

            window.showToast = (type, message) => {
                const config = types[type] || types.success;
                const element = document.getElementById('toast-template').content.firstElementChild.cloneNode(true);

                element.classList.add(`app-toast-${type in types ? type : 'success'}`);
                element.setAttribute('role', type === 'success' ? 'status' : 'alert');
                element.dataset.bsDelay = config.delay;
                element.querySelector('.toast-icon').classList.add(config.icon);
                element.querySelector('[data-toast-title]').textContent = config.title;
                element.querySelector('[data-toast-message]').textContent = message;

                stack.appendChild(element);
                show(element);
            };

            // Erros de validação de campos que não existem nesta tela: mostra a mensagem no balão
            const validation = stack.querySelector('[data-validation-toast]');
            if (validation) {
                const unplaced = [...validation.querySelectorAll('[data-field]')].filter((item) => {
                    const name = item.dataset.field;
                    return !document.querySelector(`[name="${CSS.escape(name)}"], [name="${CSS.escape(name)}[]"]`);
                });

                unplaced.forEach((item) => item.classList.remove('d-none'));
                if (unplaced.length) {
                    validation.querySelector('[data-unplaced-errors]').classList.remove('d-none');
                }
            }

            stack.querySelectorAll('.toast').forEach(show);

            // Eventos vindos dos componentes Livewire: $this->dispatch('toast', type: ..., message: ...)
            window.addEventListener('toast', (event) => {
                const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                window.showToast(detail.type, detail.message);
            });
        })();
    </script>
@endpush
