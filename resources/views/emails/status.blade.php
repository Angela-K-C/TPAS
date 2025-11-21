<h1>Hello, {{ $userName }}!</h1>

@if($status === "approved")
    <p>Congrats! Your temporary pass application has been {{ $status }} 🤗.</p>
    <p>Your QR code is attached as a PDF. Save it and present it at the gate.</p>
@else
    <p>Sorry, your temporary pass application has been rejected ❌</p>
    <p>Please visit the security office to apply physically for a temporary pass.</p>
@endif

<p>Thank you!</p>
