@props(['title', 'description', 'badge' => null])

<div class="mb-4">

    @if ($badge)
        <span class="badge rounded-pill px-3 py-2 mb-3" style="background-color: #fff1e6; color: #ff6500;">
            {{ $badge }}
        </span>
    @endif

    <h3 class="fw-bold mb-2">
        {{ $title }}
    </h3>

    <p class="text-secondary mb-0">
        {{ $description }}
    </p>

</div>
