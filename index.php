<!DOCTYPE html>
<html>
<head>
    <title>API NBP Szymon Czopek</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <div id="mainDiv">
            <div>
                <h1>API NBP</h1>
            </div>
            <div>
                <button type="button" id="buttonGetAll">Get currency rates</button>
            </div>
        </div>

        <table id="TableDiv">
            <thead id="TableHead">

            </thead>
            <tbody id="TableBody">
            </tbody>
        </table>
        <footer>Szymon Czopek</footer>
    </main>
</body>
<script>
    const buttonGetAll = document.getElementById('buttonGetAll');
    const messageDiv = document.createElement('div');
    const mainDiv = document.getElementById('mainDiv');
    const tableDiv = document.getElementById('TableDiv');
    const tableBody = document.getElementById('TableBody');

    function displayHeader(headers){
        var TableHeadDiv = document.getElementById("TableHead");
        const tableHeaderRow = document.createElement('tr');

        headers.forEach((element)=>{
            const label = document.createElement('th');
            label.textContent = element;
            tableHeaderRow.appendChild(label);
        })
        TableHeadDiv.innerHTML= '';
        TableHeadDiv.appendChild(tableHeaderRow);
    }

    function displayRates(data){
        const headers = ['Currency','Code','PLN'];
        displayHeader(headers);
        var counter = 1

        data.forEach((row) => {
            const newRow = document.createElement('tr');
            Object.keys(row).forEach(cell => {
                const newCell = document.createElement('td');
                newCell.textContent = row[cell];
                newRow.appendChild(newCell);
                counter++;
            })
            tableBody.append(newRow);
        })
    }

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
                message = data[1]['message'];
                //rates.forEach(element => console.log(element['currency'] + ' ' + element['mid']));
                displayRates(rates);
            })
            .catch((error) => {
                isError = true;
            });

        messageDiv.textContent = message;
        if (isError) {
            messageDiv.style.color = 'red';
        }
        else messageDiv.style.color = 'black';
        TableDiv.after(messageDiv)
    })

</script>
</body>
</html>