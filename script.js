var form = document.forms[0], 
    submit = document.getElementById('submit'),
    input = document.getElementById('search'),
    clear = document.getElementById('clear');
    
 
//search functionality
input.addEventListener('invalid', function(e) {
    if(input.validity.valueMissing){
        e.target.setCustomValidity("Please enter Name or Symbol"); 
    } else if(!input.validity.valid) {
        e.target.setCustomValidity("This is not a valid Name or Symbol"); 
    } 
    
    input.addEventListener('input', function(e){
        e.target.setCustomValidity('');
    });
}, false);

//clear functionality
function clearStock() {
    search.value = "";
    results.remove();
}