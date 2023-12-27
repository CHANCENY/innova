function checkInputValidate()
{
    const inputField = document.getElementById('storage_name');
    const validationMessage = document.getElementById('validationMessage');

    inputField.addEventListener('input', function () {
        const inputValue = inputField.value;
        validateUri(inputValue);
    });
}

function validateUri(inputValue)
{
    const validationMessage = document.getElementById('validationMessage');

    const jsonURI = JSON.parse(document.getElementById("uris").textContent);

    const isDuplicate = jsonURI.includes(inputValue.toLowerCase());

    if (isDuplicate) {
        validationMessage.textContent = 'Storage already exists.';
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

    const tbody = document.getElementById("storage_body_fields");
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
    return `<td>${index}</td>
                                        <td>
                                            <input id="storage_name" name="field_${index}" class="form-control" type="text" style="min-width:150px">
                                           
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="type_${index}" class="select form-control">
                                                    <option>Please Select</option>
                                                    <option value="number">Number</option>
                                                    <option value="short text">Short text</option>
                                                    <option value="long text">Long text</option>
                                                    <option value="short">Boolean</option>
                                                    <option value="file large">Large file</option>
                                                    <option value="file medium">Medium file</option>
                                                    <option value="file small">Small file</option>
                                                    <option value="time create">On create timestamp</option>
                                                    <option value="time update">On update timestamp</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="option_${index}" class="select form-control">
                                                    <option>Please Select</option>
                                                    <option value="not empty">Not empty</option>
                                                    <option value="empty">Empty</option>
                                                    <option value="primary key">Primary Key</option>
                                                    <option value="unique">Unique</option>
                                                    <option value="on create">On create</option>
                                                    <option value="on update">On update</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="addSeasonForm()" class="text-success font-18 mx-2" title="Add"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" onclick="removeSeasonForm(this)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a>
                                        </td>`;
}
