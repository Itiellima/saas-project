@extends('layouts.main')

@section('title', 'Equipe - OficinaOS')
@section('page_title', 'Equipe')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="d-flex align-items-center gap-2 mb-4">

            <div class="rounded-3 d-flex align-items-center justify-content-center"
                style="width: 45px; height: 45px; background-color: #fff1e6;">
                <i class="bi bi-people" style="color: #ff6500;"></i>
            </div>

            <div>
                <h3 class="fw-bold mb-0">Equipe</h3>
                <small class="text-secondary">Quem tem acesso ao sistema da sua oficina.</small>
            </div>

        </div>


        <div class="row g-4">

            <div class="col-12 col-xl-7">

                {{-- SENHA PROVISÓRIA (aparece uma única vez) --}}
                @if ($credentials = session('temporary_password'))
                    <div class="card border-0 shadow-sm mb-4" style="border-left: 5px solid #198754 !important;">
                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-key me-2 text-success"></i>
                                Dados de acesso de {{ $credentials['name'] }}
                            </h5>
                            <p class="text-secondary small mb-3">
                                Repasse ao funcionário. <strong>Esta senha não será mostrada de novo</strong>;
                                no primeiro acesso ele vai criar uma senha própria.
                            </p>

                            <dl class="row mb-3">
                                <dt class="col-4 col-sm-3 text-secondary fw-normal">E-mail</dt>
                                <dd class="col-8 col-sm-9 fw-semibold">{{ $credentials['email'] }}</dd>

                                <dt class="col-4 col-sm-3 text-secondary fw-normal">Senha</dt>
                                <dd class="col-8 col-sm-9 mb-0">
                                    <code class="fs-5" id="temporary-password">{{ $credentials['password'] }}</code>
                                </dd>
                            </dl>

                            <button type="button" class="btn btn-sm btn-outline-success" id="copy-credentials"
                                data-text="Acesse {{ url('/login') }} com o e-mail {{ $credentials['email'] }} e a senha provisória {{ $credentials['password'] }}">
                                <i class="bi bi-clipboard me-1"></i>
                                Copiar mensagem para enviar
                            </button>

                        </div>
                    </div>
                @endif


                {{-- MEMBROS --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-person-badge me-2" style="color: #ff6500;"></i>
                            Membros ({{ $members->count() }})
                        </h5>
                    </div>

                    <div class="list-group list-group-flush px-2 pb-2">
                        @foreach ($members as $member)
                            <div class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-2">

                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate">
                                        {{ $member->name }}
                                        @if ($member->is(Auth::user()))
                                            <small class="text-secondary fw-normal">(você)</small>
                                        @endif
                                    </div>
                                    <small class="text-secondary">{{ $member->email }}</small>
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                    @if ($member->must_change_password)
                                        <span class="badge text-bg-warning" title="Ainda não trocou a senha provisória">
                                            Aguardando 1º acesso
                                        </span>
                                    @endif

                                    <span class="badge {{ $member->isOwner() ? 'text-bg-dark' : 'text-bg-light border' }}">
                                        {{ $member->isOwner() ? 'Dono' : 'Funcionário' }}
                                    </span>

                                    @unless ($member->isOwner())
                                        <form action="{{ route('team.reset-password', $member->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                title="Gerar nova senha provisória"
                                                onclick="return confirm('Gerar uma nova senha provisória para {{ $member->name }}? A senha atual deixa de funcionar.')">
                                                <i class="bi bi-key"></i>
                                            </button>
                                        </form>
                                    @endunless

                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

            </div>


            {{-- ADICIONAR --}}
            <div class="col-12 col-xl-5">

                <form action="{{ route('team.store') }}" method="POST" class="card border-0 shadow-sm">

                    @csrf

                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-person-plus me-2" style="color: #ff6500;"></i>
                            Adicionar funcionário
                        </h5>
                        <small class="text-secondary">
                            O sistema gera uma senha provisória para você repassar.
                        </small>
                    </div>

                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nome</label>
                            <input type="text" id="name" name="name" required maxlength="255"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">E-mail</label>
                            <input type="email" id="email" name="email" required maxlength="255"
                                placeholder="usado para entrar no sistema"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #ff6500;">
                            <i class="bi bi-plus-lg me-2"></i>
                            Adicionar à equipe
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document.getElementById('copy-credentials')?.addEventListener('click', async (event) => {
            const button = event.currentTarget;

            try {
                await navigator.clipboard.writeText(button.dataset.text);
                window.showToast('success', 'Mensagem copiada. Cole no WhatsApp ou onde preferir.');
            } catch (error) {
                window.showToast('warning', 'Não foi possível copiar. Selecione a senha e copie manualmente.');
            }
        });
    </script>
@endpush
