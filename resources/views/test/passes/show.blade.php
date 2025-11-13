@include('test.navbar')

<h1>Temporary Pass Details</h1>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $pass->id }}</td>
    </tr>
    <tr>
        <th>Holder</th>
        <td>{{ $pass->passable->name ?? 'Guest' }}</td>
    </tr>
    <tr>
        <th>Reason</th>
        <td>{{ $pass->reason_label }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ ucfirst($pass->status) }}</td>
    </tr>
    <tr>
        <th>Valid From</th>
        <td>{{ $pass->valid_from?->format('Y-m-d H:i') ?? '-'  }}</td>
    </tr>
    <tr>
        <th>Valid Until</th>
        <td>{{ $pass->valid_until?->format('Y-m-d H:i') ?? '-' }}</td>
    </tr>
    <tr>
        <th>Approved By</th>
        <td>{{ $pass->approver->name ?? '-' }}</td>
    </tr>
</table>

@auth('web')
    @if($pass->status === 'pending')
        <form action="{{ route('passes.update', $pass) }}" method="POST" style="display:inline;">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="approved">
            <button class="btn btn-success btn-sm">Approve</button>
        </form>

        <form action="{{ route('passes.update', $pass) }}" method="POST" style="display:inline;">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <button class="btn btn-danger btn-sm">Reject</button>
        </form>
    @endif
@endauth

<h2>QR code here (to download temporary pass) 👎</h2>
<img src="https://hexdocs.pm/qr_code/2.2.1/docs/qrcode.svg" alt="QR Code" width="100" height="100" />

<br><br>
<a href="{{ route('passes.index') }}">Back to all passes</a>