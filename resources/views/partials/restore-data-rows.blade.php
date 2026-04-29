@forelse ($items as $item)
    <tr>
        <td>{{ $item['tanggal'] }}</td>
        <td>{{ $item['jam'] }}</td>
        <td>{{ $item['username'] }}</td>
        <td>{{ $item['ip_address'] }}</td>
        <td>{{ $item['longitude'] }}</td>
        <td>{{ $item['latitude'] }}</td>
        <td>{{ $item['jenis'] }}</td>
        <td>{{ $item['data'] }}</td>
        <td class="actions">
            <div class="action-inline">
                <form method="POST" action="{{ route('dashboard.restore-data.restore', ['restoreid' => $item['id']]) }}" onsubmit="return confirm('Restore data ini?');">
                    @csrf
                    <button type="submit" class="btn btn-restore">Restore</button>
                </form>
                <form method="POST" action="{{ route('dashboard.restore-data.force-delete', ['restoreid' => $item['id']]) }}" onsubmit="return confirm('Hapus permanen data ini? Aksi ini tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="empty">Belum ada data pada restore data.</td>
    </tr>
@endforelse
