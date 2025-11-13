@include('test.navbar')

@if (session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

<h1>Temporary Passes</h1>

<a href="{{ route('passes.create') }}">Appply for pass</a>

@if($passes->isEmpty())
    <p>No temporary passes found.</p>
@else
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Holder</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Valid From</th>
                <th>Valid Until</th>
                @auth('web')
                    <th>Actions</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($passes as $pass)
                <tr>
                    <td>{{ $pass->id }}</td>
                    <td>{{ $pass->passable->name ?? 'Guest' }}</td>
                    <td>{{ $pass->reason_label }}</td>
                    <td>{{ ucfirst($pass->status) }}</td>
                    <td>{{ $pass->valid_from?->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>{{ $pass->valid_until?->format('Y-m-d H:i') ?? '-' }}</td>

                    @if(Auth::guard('university')->check() || Auth::guard('guest')->check())
                        <td>
                            <a href="{{ route('passes.show', $pass->id) }}">View</a>
                        </td>
                    @endif
                    
                    @auth('web')
                        <td>
                            @if($pass->status === 'pending')
                                <form action="{{ route('passes.update', $pass) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="approved">
                                    <button>Approve</button>
                                </form>

                                <form action="{{ route('passes.update', $pass) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="rejected">
                                    <button>Reject</button>
                                </form>

                                <form action="{{ route('passes.destroy', $pass) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" name="status" value="rejected">
                                    <button>Delete</button>
                                </form>
                            @else
                                {{ ucfirst($pass->status) }}
                            @endif
                        </td>
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
