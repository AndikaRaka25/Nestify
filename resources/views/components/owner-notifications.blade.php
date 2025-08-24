<div>
    <h4 class="mb-2">Notifikasi</h4>

    @forelse(auth()->user()->unreadNotifications as $notif)
        <div class="p-3 mb-3 border rounded">
            <strong>{{ $notif->data['title'] ?? 'Notifikasi' }}</strong><br>
            <span>{{ $notif->data['message'] ?? '' }}</span><br>
            @if(!empty($notif->data['url']))
                <a href="{{ $notif->data['url'] }}" class="text-primary">Lihat detail</a>
            @endif

            <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="inline">
                @csrf
                <button class="btn btn-sm btn-link">Tandai dibaca</button>
            </form>
        </div>
    @empty
        <p>Tidak ada notifikasi baru.</p>
    @endforelse
</div>
