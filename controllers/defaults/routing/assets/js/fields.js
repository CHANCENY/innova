function checkInputValidate()
{
    const inputField = document.getElementById('route_uri');
    const validationMessage = document.getElementById('validationMessage');

    inputField.addEventListener('input', function () {
        const inputValue = inputField.value;
        const isValid = /^[a-zA-Z/]*$/.test(inputValue); // Regular expression to allow only letters or /

        if (isValid) {
            validationMessage.textContent = ''; // Clear validation message
            validateUri(inputValue);
        } else {
            validationMessage.textContent = 'Invalid input. Only letters or / are allowed.';
        }
    });
}

function validateUri(inputValue)
{
    const validationMessage = document.getElementById('validationMessage');

    const jsonURI = JSON.parse(document.getElementById("uris").textContent);

    const isDuplicate = jsonURI.includes(inputValue.toLowerCase());

    if (isDuplicate) {
        validationMessage.textContent = 'uri already exists.';
    } else {
        validationMessage.textContent = '';
    }
}

checkInputValidate();

function addSeasonForm()
{
    let seasonCount = localStorage.getItem("params_count");
    if(seasonCount === null)
    {
        seasonCount = 1;
    }

    const tbody = document.getElementById("params_body");
    if(tbody !== null)
    {
        seasonCount = parseInt(seasonCount) + 1;
        const tds = buildField(seasonCount);
        const tr = document.createElement("tr");
        tr.innerHTML = tds;
        tbody.appendChild(tr);
        localStorage.setItem("params_count", seasonCount.toString());
    }
}

function removeSeasonForm(evt)
{
    let seasonCount = localStorage.getItem("params_count");
    if(parseInt(seasonCount) > 1)
    {
        var row = evt.closest("tr");
        if(row !== null)
        {
            row.remove();
            seasonCount = parseInt(seasonCount) - 1;
            localStorage.setItem("params_count", seasonCount.toString());
        }
    }
}

function buildField(index)
{
    return `
                                        <td>${index}</td>
                                        <td>
                                            <input class="form-control" name="param_key_${index}" type="text" style="min-width:150px">
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="params_type_${index}" class="select form-control">
                                                    <option>Select Type</option>
                                                    <option value="string">string</option>
                                                    <option value="numerical">numerical</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="params_option_${index}" style="width:150px" class="select form-control">
                                                <option>Select Type</option>
                                                <option value="required">Required</option>
                                                <option value="optional">Optional</option>
                                            </select>
                                        </td>
                                        <td>
                                         <a href="javascript:void(0)" onclick="addSeasonForm()" class="text-success font-18 mx-2" title="Add"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" onclick="removeSeasonForm(this)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    `;
}
