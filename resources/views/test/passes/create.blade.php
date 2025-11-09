@include('test.navbar')

<div>
    <h1>Apply for Temporary Pass</h1>

    <form action="{{ route('passes.store') }}" method="POST">
        @csrf

        <label for="reason">Reason:</label>
        <select name="reason" id="reason" required>
            <option value="">-- Select Reason --</option>
            @foreach(\App\Models\TemporaryPass::reasonLabels() as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <br><br>
        <button type="submit">Submit</button>
    </form>
</div>