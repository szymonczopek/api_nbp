<!DOCTYPE html>
<html>
<head>
    <title>API NBP Szymon Czopek</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="mainDiv">
        <div>
            <h1>API NBP</h1>
        </div>
        <div>
            <button type="button" id="buttonGetAll">Pobierz kursy walut</button>
        </div>
    </div>
<script>
    const buttonGetAll = document.getElementById('buttonGetAll');
    const messageDiv = document.createElement('div');
    const mainDiv = document.getElementById('mainDiv');

    buttonGetAll.addEventListener('click', async () => {
         var isError = false;
         var message = '';
        await fetch('http://localhost:63342/api_nbp/controllers/NBPAPI.php', {
            headers: {
                'Content-Type': 'application/json',
            },
            method: 'GET',
        })
            .then(async (response) => await response.json())
            .then(async (data) => {
                if (data.error) {
                    const error = `<p>${data.error}</p>`
                    messageDiv.innerHTML = error;
                }

                const rates = data[0]['rates'];
                message = data[1]['message']
                rates.forEach(element => console.log(element['currency'] + ' ' + element['mid']))
            })
            .catch((error) => {
                isError = true;
            });

        messageDiv.textContent = message;
        if (isError) {
            messageDiv.style.color = 'red';
        }
        else messageDiv.style.color = 'black';
       mainDiv.after(messageDiv);

    })

</script>
</body>
</html>