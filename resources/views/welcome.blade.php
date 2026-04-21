<!DOCTYPE html>
<html>
<head>
    <title>AI Doc Chat</title>

    @vite('resources/css/app.css')
</head>

<body>

<div class="container">

    <!-- Upload -->
    <div class="upload-box">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <!-- Chat -->
    <div id="chat" class="chat-box"></div>

    <!-- Input -->
    <div class="input-area">
        <input id="message" type="text" placeholder="Ask something..." />
        <button onclick="sendMessage()">Send</button>
    </div>

</div>

<script>
    const chatBox = document.getElementById('chat');

    function addMessage(text, type) {
        const div = document.createElement('div');
        div.classList.add('message', type);
        div.innerText = text;
        chatBox.appendChild(div);

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function sendMessage() {
        const input = document.getElementById('message');
        const text = input.value;

        if (!text) return;

        addMessage(text, 'user');
        input.value = '';

        // typing indicator
        const typing = document.createElement('div');
        typing.classList.add('message', 'bot');
        typing.innerText = 'Typing...';
        chatBox.appendChild(typing);

        const res = await fetch('/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question: text })
        });

        const data = await res.json();

        typing.remove();
        addMessage(data.answer, 'bot');
    }

    // Upload handler
    document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        await fetch('/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        alert('File uploaded & processed!');
    });
</script>

</body>
</html>