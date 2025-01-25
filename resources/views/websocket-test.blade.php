<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        });

        const channel = pusher.subscribe('my-channel');

        sendMessage = () => {
            channel.trigger('my-channel', 'my-event', {
                message: 'Hello, world!'
            });
        }

    </script>
</head>
<body>
    <h1>WebSocket Test</h1>
    <input type="text" id="message" placeholder="Enter your message">
    <button onclick="sendMessage()">Send Message</button>
    <h2>Messages:</h2>
    <ul id="messages"></ul>
</body>
</html>
