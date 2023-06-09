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
            <div id="eventsBar">
                <button type="button" id="buttonGetAll">Get currency rates</button>
                <button type="button" id="buttonDisplayCalculator">Convert currencies</button>
            </div>
        </div>

        <table id="TableDiv">
            <thead id="TableHead">

            </thead>
            <tbody id="TableBody">
            </tbody>
        </table>
        <footer><a href="https://github.com/szymonczopek">Github Szymon Czopek</a></footer>
    </main>
</body>
<script>
    const buttonGetAll = document.getElementById('buttonGetAll');
    const buttonDisplayCalculator = document.getElementById('buttonDisplayCalculator');
    const messageDiv = document.createElement('div');
    const mainDiv = document.getElementById('mainDiv');
    const tableDiv = document.getElementById('TableDiv');
    const tableBody = document.getElementById('TableBody');
    const eventsBar = document.getElementById('eventsBar');

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
        var counter = 1;

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
        await fetch('http://localhost:63342/api_nbp/routes.php?page=getAll', {
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

    buttonDisplayCalculator.addEventListener('click', async () => {
        const currencies = ["USD", "EUR", "GBP", "JPY"];
        displayCalculator(currencies)


    })

    function displayCalculator(currencies){
       const calculatorDiv = document.createElement('div');
       calculatorDiv.id = 'calculatorDiv';
        const inputCurrency = document.createElement("input");
        const selectCurrency1 = document.createElement("select");
        const arrowDiv = document.createElement("div");
        arrowDiv.id = 'arrowDiv';
        arrowDiv.textContent = '\u2192';
        arrowDiv.style.fontSize = '40px';
        const selectCurrency2 = document.createElement("select");
        const convertButton = document.createElement("button");
        convertButton.id = 'convertButton';
        convertButton.textContent = 'Convert';

        createOptions(selectCurrency1, currencies)
        createOptions(selectCurrency2, currencies)

        calculatorDiv.appendChild(inputCurrency)
        calculatorDiv.appendChild(selectCurrency1)
        calculatorDiv.appendChild(arrowDiv)
        calculatorDiv.appendChild(selectCurrency2)
        calculatorDiv.appendChild(convertButton)
        eventsBar.appendChild(calculatorDiv)

    }
    function createOptions(selectElement, optionsArray) {
        for (let i = 0; i < optionsArray.length; i++) {
            const option = document.createElement("option");
            option.value = optionsArray[i];
            option.textContent = optionsArray[i];
            selectElement.appendChild(option);
        }
    }
</script>
</body>
</html>