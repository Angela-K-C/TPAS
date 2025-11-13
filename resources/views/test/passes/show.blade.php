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

@if($pass->qr_code_token)
    <section style="margin-top: 2rem;">
        <h2>Your QR code</h2>
        <p>The guard will scan this code or enter token <strong>{{ strtoupper(substr($pass->qr_code_token, 0, 8)) }}</strong>.</p>
        <img src="{{ route('passes.qr.image', $pass) }}" alt="Temporary pass QR code" width="220" height="220" style="border:1px solid #ddd; padding:8px; background:#fff;">
        <div style="margin-top: 0.5rem;">
            <a href="{{ route('passes.qr.image', $pass) }}" download="temporary-pass-{{ $pass->id }}.svg">Download QR</a>
        </div>
    </section>
@endif

<br><br>
<a href="{{ route('passes.index') }}">Back to all passes</a>
