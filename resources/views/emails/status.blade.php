<h1>Hello, {{ $userName }}!</h1>

@if($status === "approved")
    <p>Congrats! Your temporary pass application has been {{ $status }} 🤗.</p>
@else
    <p>Sorry, your temporary pass application has been rejected ❌</p>
    <p>Please visit the security office to apply physically for a temporary pass.</p>
@endif

<p>Thank you!</p>
