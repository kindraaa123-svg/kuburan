@forelse ($logs as $log)
    <tr>
        <td>{{ $log['tanggal'] }}</td>
        <td>{{ $log['jam'] }}</td>
        <td>{{ $log['nama'] }}</td>
        <td>{{ $log['username'] }}</td>
        <td>{{ $log['ip_address'] }}</td>
        <td>{{ $log['longitude'] }}</td>
        <td>{{ $log['latitude'] }}</td>
        <td>{{ $log['aksi'] }}</td>
        <td class="detail" title="{{ $log['detail'] }}">{{ \Illuminate\Support\Str::limit((string) $log['detail'], 140) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="empty">Belum ada data activity log.</td>
    </tr>
@endforelse
