@extends('layouts.main')

@section('title', 'Dados da oficina - OficinaOS')
@section('page_title', 'Oficina')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="d-flex align-items-center gap-2 mb-4">

            <div class="rounded-3 d-flex align-items-center justify-content-center"
                style="width: 45px; height: 45px; background-color: #fff1e6;">
                <i class="bi bi-building" style="color: #ff6500;"></i>
            </div>

            <div>
                <h3 class="fw-bold mb-0">Dados da oficina</h3>
                <small class="text-secondary">Aparecem no cabeçalho das ordens de serviço impressas.</small>
            </div>

        </div>


        <div class="row justify-content-center">

            <div class="col-12 col-xl-9">

                <form action="{{ route('workshop.update') }}" method="POST" novalidate>

                    @csrf
                    @method('PUT')

                    {{-- IDENTIFICAÇÃO E CONTATO --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-person-vcard me-2" style="color: #ff6500;"></i>
                                Identificação e contato
                            </h5>
                            <small class="text-secondary">Como a oficina aparece para os seus clientes.</small>
                        </div>

                        <div class="card-body p-4">

                            <div class="row g-3">

                                <div class="col-12 col-md-7">
                                    <label for="name" class="form-label fw-semibold">Nome da oficina</label>
                                    <input type="text" id="name" name="name" required maxlength="255"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $tenant->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="document" class="form-label fw-semibold">
                                        CPF ou CNPJ <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="document" name="document" inputmode="numeric"
                                        data-mask="document" maxlength="18" placeholder="00.000.000/0000-00"
                                        class="form-control @error('document') is-invalid @enderror"
                                        value="{{ old('document', $tenant->formattedDocument()) }}">
                                    @error('document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-7">
                                    <label for="email" class="form-label fw-semibold">
                                        E-mail de contato <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="email" id="email" name="email" maxlength="255"
                                        placeholder="contato@suaoficina.com.br"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $tenant->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="phone" class="form-label fw-semibold">
                                        Telefone / WhatsApp <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" inputmode="numeric"
                                        data-mask="phone" maxlength="15" placeholder="(00) 00000-0000"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $tenant->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ENDEREÇO --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-geo-alt me-2" style="color: #ff6500;"></i>
                                Endereço
                            </h5>
                            <small class="text-secondary">Digite o CEP para preencher rua, bairro e cidade.</small>
                        </div>

                        <div class="card-body p-4">

                            <div class="row g-3">

                                <div class="col-12 col-md-4">
                                    <label for="zip_code" class="form-label fw-semibold">CEP</label>
                                    <div class="input-group has-validation">
                                        <input type="text" id="zip_code" name="zip_code" inputmode="numeric"
                                            data-mask="zip" maxlength="9" placeholder="00000-000"
                                            class="form-control @error('zip_code') is-invalid @enderror"
                                            value="{{ old('zip_code', $tenant->formattedZipCode()) }}">
                                        <span class="input-group-text bg-white d-none" id="zip-loading">
                                            <span class="spinner-border spinner-border-sm text-secondary"></span>
                                        </span>
                                        @error('zip_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text text-danger d-none" id="zip-feedback"></div>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label for="street" class="form-label fw-semibold">Rua</label>
                                    <input type="text" id="street" name="street" maxlength="255"
                                        class="form-control @error('street') is-invalid @enderror"
                                        value="{{ old('street', $tenant->street) }}">
                                    @error('street')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-5 col-md-3">
                                    <label for="number" class="form-label fw-semibold">Número</label>
                                    <input type="text" id="number" name="number" maxlength="20"
                                        class="form-control @error('number') is-invalid @enderror"
                                        value="{{ old('number', $tenant->number) }}">
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-7 col-md-4">
                                    <label for="complement" class="form-label fw-semibold">Complemento</label>
                                    <input type="text" id="complement" name="complement" maxlength="255"
                                        placeholder="Galpão 2, fundos..."
                                        class="form-control @error('complement') is-invalid @enderror"
                                        value="{{ old('complement', $tenant->complement) }}">
                                    @error('complement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-5">
                                    <label for="district" class="form-label fw-semibold">Bairro</label>
                                    <input type="text" id="district" name="district" maxlength="255"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district', $tenant->district) }}">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-8 col-md-9">
                                    <label for="city" class="form-label fw-semibold">Cidade</label>
                                    <input type="text" id="city" name="city" maxlength="255"
                                        class="form-control @error('city') is-invalid @enderror"
                                        value="{{ old('city', $tenant->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-4 col-md-3">
                                    <label for="state" class="form-label fw-semibold">UF</label>
                                    <select id="state" name="state" class="form-select @error('state') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach (\App\Http\Requests\WorkshopRequest::STATES as $uf)
                                            <option value="{{ $uf }}" @selected(old('state', $tenant->state) === $uf)>{{ $uf }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="d-flex justify-content-end mb-4">
                        <button type="submit" class="btn text-white fw-semibold px-4" style="background-color: #ff6500;">
                            <i class="bi bi-check me-2"></i>
                            Salvar alterações
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        (() => {
            const onlyDigits = (value) => value.replace(/\D/g, '');

            // Máscaras: aplicadas enquanto digita; o servidor guarda só os números
            const masks = {
                document(d) {
                    d = d.slice(0, 14);
                    if (d.length <= 11) {
                        return d.replace(/(\d{3})(\d)/, '$1.$2')
                            .replace(/(\d{3})(\d)/, '$1.$2')
                            .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    }
                    return d.replace(/^(\d{2})(\d)/, '$1.$2')
                        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                        .replace(/\.(\d{3})(\d)/, '.$1/$2')
                        .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
                },
                phone(d) {
                    d = d.slice(0, 11);
                    if (d.length <= 10) {
                        return d.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d{1,4})$/, '$1-$2');
                    }
                    return d.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d{1,4})$/, '$1-$2');
                },
                zip(d) {
                    return d.slice(0, 8).replace(/^(\d{5})(\d)/, '$1-$2');
                },
            };

            document.querySelectorAll('[data-mask]').forEach((input) => {
                const apply = () => { input.value = masks[input.dataset.mask](onlyDigits(input.value)); };
                input.addEventListener('input', apply);
                apply();
            });

            // Busca do endereço pelo CEP (ViaCEP). Se falhar, a pessoa preenche à mão.
            const zip = document.getElementById('zip_code');
            const loading = document.getElementById('zip-loading');
            const feedback = document.getElementById('zip-feedback');
            const field = (id) => document.getElementById(id);
            // Evita repetir a mesma busca enquanto o CEP não muda; é "esquecido" quando o CEP fica
            // incompleto, para que apagar e digitar o mesmo CEP (inclusive o já salvo) busque de novo
            let lastSearched = null;

            const showFeedback = (message) => {
                feedback.textContent = message;
                feedback.classList.toggle('d-none', !message);
            };

            zip.addEventListener('input', async () => {
                const cep = onlyDigits(zip.value);
                showFeedback('');

                if (cep.length !== 8) {
                    lastSearched = null;
                    return;
                }

                if (cep === lastSearched) {
                    return;
                }
                lastSearched = cep;

                loading.classList.remove('d-none');

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();

                    if (!response.ok || data.erro) {
                        showFeedback('CEP não encontrado. Preencha o endereço manualmente.');
                        return;
                    }

                    field('street').value = data.logradouro || '';
                    field('district').value = data.bairro || '';
                    field('city').value = data.localidade || '';
                    field('state').value = data.uf || '';
                    field('number').focus();
                } catch (error) {
                    showFeedback('Não foi possível buscar o CEP agora. Preencha o endereço manualmente.');
                } finally {
                    loading.classList.add('d-none');
                }
            });
        })();
    </script>
@endpush
