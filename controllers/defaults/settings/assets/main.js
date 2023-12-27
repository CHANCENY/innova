
const activeInput = document.getElementById("active");
activeInput.addEventListener("change", (e)=>{
  const added = document.getElementById("added");
  if(added !== null) {
    added.remove();
  }
  if(e.target.value === "2fa") {
    const div = document.createElement("div");
    div.className = "form-group row";
    div.id = "added";
    div.innerHTML = addConfigureField();
    const pr = activeInput.parentNode.parentNode;
    jQuery(div).insertAfter(pr);
  }
})

function addConfigureField() {
  return `
              <label for="active" class="col-md-3 col-form-label">Transmission</label>
              <div class="col-md-9">
               <select name="transmission" class="form-control">
                 <option>--Select default---</option>
                 <option value="sms" selected>SMS</option>
                 <option value="email">Email</option>
                 <option value="app">Authenticator App</option>
               </select>
              </div>
            `
}