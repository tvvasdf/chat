<?php

if (!User::authorized()) {
    exit('No AUTH!');
}

$client = new WebSocket\Client("ws://localhost:8080");
$client->text(json_encode([]));
//echo $client->receive();
$client->close();

global $user;
?>

<script>
    const socket = new WebSocket('ws://localhost:8080')
    socket.onmessage = function (data) {
        console.log(data)
    }

    document.addEventListener('DOMContentLoaded', function(){
        const form = document.querySelector('form#test')
        form.addEventListener('submit', function (e) {
            e.preventDefault()
            let data = {}
            this.querySelectorAll('input[type=text]').forEach(function (element, index) {
                data['input_' + index] = element.value
            })
            socket.send(JSON.stringify(data))
        })
    })

</script>


<form method="post" id="test">
    <input type="text" name="data" />
    <input type="submit">
</form>

