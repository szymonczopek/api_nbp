<!DOCTYPE html>
<html>
<head>
    <title>API NBP Szymon Czopek</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://imageupload.io/ib/TJLMOL9Mnnifdtj_1686512943.png">
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
                <button type="button" id="buttonDisplayHistory">Conversion history</button>
            </div>
        </div>

        <table id="TableDiv">
            <thead id="TableHead">

            </thead>
            <tbody id="TableBody">
            </tbody>
        </table>
        <footer><a href="https://github.com/szymonczopek/api_nbp">Github Szymon Czopek</a></footer>
    </main>
</body>
<script>
    const buttonGetAll = document.getElementById('buttonGetAll');
    const buttonDisplayCalculator = document.getElementById('buttonDisplayCalculator');
    const buttonDisplayHistory = document.getElementById('buttonDisplayHistory');
    const messageDiv = document.createElement('div');
    const mainDiv = document.getElementById('mainDiv');
    const tableDiv = document.getElementById('TableDiv');
    const tableBody = document.getElementById('TableBody');
    const eventsBar = document.getElementById('eventsBar');

    const url = 'https://api-nbp-szymonczopek.herokuapp.com/routes.php?page=';
    //const url = 'http://localhost:63342/api_nbp/routes.php?page=';

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

        data.forEach((row) => {
            const newRow = document.createElement('tr');
            Object.keys(row).forEach(cell => {
                const newCell = document.createElement('td');
                newCell.textContent = row[cell];
                newRow.appendChild(newCell);
            })
            tableBody.append(newRow);
        })
    }
    function displayHistory(data){
        const headers = ['Input','From','','Value','To','','Value','Conversion'];
        displayHeader(headers);

        data.forEach((row) => {
            const newRow = document.createElement('tr');
            Object.keys(row).forEach(cell => {
                const newCell = document.createElement('td');
                newCell.textContent = row[cell];
                newRow.appendChild(newCell);
            })
            tableBody.append(newRow);
        })
    }
    function displayCalculator(currencies){
        const calculatorDiv = document.getElementById('calculatorDiv');
        if (calculatorDiv) {
            return;
        }
        else {
            const calculatorDiv = document.createElement('div');
            calculatorDiv.id = 'calculatorDiv';
            const inputCalculator = document.createElement("input");
            inputCalculator.id = 'inputCalculator';
            const selectCode1 = document.createElement("select");
            selectCode1.id = 'selectCode1';
            const arrowDiv = document.createElement("div");
            arrowDiv.id = 'arrowDiv';
            arrowDiv.textContent = '\u2192';
            const resultDiv = document.createElement("div");
            resultDiv.id = 'resultDiv';
            const selectCode2 = document.createElement("select");
            selectCode2.id = 'selectCode2';
            const convertButton = document.createElement("button");
            convertButton.id = 'convertButton';
            convertButton.textContent = 'Convert';



            createOptions(selectCode1, currencies);
            createOptions(selectCode2, currencies);

            calculatorDiv.appendChild(inputCalculator);
            calculatorDiv.appendChild(selectCode1);
            calculatorDiv.appendChild(arrowDiv);
            calculatorDiv.appendChild(resultDiv);
            calculatorDiv.appendChild(selectCode2);
            calculatorDiv.appendChild(convertButton);

            buttonDisplayCalculator.after(calculatorDiv);

            return convertButton;
        }
    }
    function createOptions(selectElement, optionsArray) {
        for (let i = 0; i < optionsArray.length; i++) {
            const option = document.createElement("option");
            option.value = optionsArray[i]['code'];
            option.id = i + 1;
            option.textContent = optionsArray[i]['code'];
            selectElement.appendChild(option);
        }
    }

    buttonGetAll.addEventListener('click', async () => {
         var isError = false;
         var message = '';

        await fetch(url+'getAll', {
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
                tableBody.innerHTML = '';
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
        var isError = false;
        var message = '';
        await fetch(url+'getCodes', {
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

                const currencies = data[0]

                const convertButton = displayCalculator(currencies)
                if(convertButton) {
                    convertButton.addEventListener('click', async () => {
                        const selectCode1 = document.getElementById('selectCode1').value;
                        const selectCode2 = document.getElementById('selectCode2').value;
                        const inputCalculator = document.getElementById('inputCalculator').value;

                        var isError = false;
                        await fetch(url+`convert&code1=${selectCode1}&code2=${selectCode2}&inputCalculator=${inputCalculator}`, {
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

                                const resultDiv = document.getElementById('resultDiv');
                                resultDiv.innerHTML = `<p>${data}</p>`;
                            })
                            .catch((error) => {
                                isError = true;
                            });
                    })
                }

            })
            .catch((error) => {
                isError = true;
            });


        if (isError) {
            messageDiv.textContent = message;
            messageDiv.style.color = 'red';
            TableDiv.after(messageDiv)
        }
    })

    buttonDisplayHistory.addEventListener('click', async () => {
        var isError = false;
        var message = '';
        await fetch(url+'getHistory', {
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
                message = data[0]['message'];
                tableBody.innerHTML = '';
                if(!message) {
                    displayHistory(data);
                }
            })
            .catch((error) => {
                isError = true;
            });

            messageDiv.textContent = message;
            messageDiv.style.color = 'red';
            eventsBar.after(messageDiv)
    })
</script>
</body>
</html>