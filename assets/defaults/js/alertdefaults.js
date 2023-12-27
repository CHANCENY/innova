

const alertstag = document.getElementById("alerts-made");
if(alertstag !== null)
{
    const contentAlert = alertstag.innerHTML;
    // Find the elements with the specified classes
    const contentElement = document.querySelector('.content');
    const rowElement = contentElement.querySelector('.row');

// Create the new content element
    const newContent = document.createElement('div');
    newContent.innerHTML = contentAlert;
    newContent.id = "alarts-remover";

// Insert the new content element between the 'content' and 'row' elements
    contentElement.insertBefore(newContent, rowElement);

    setTimeout(()=>{
        newContent.remove();
    }, 5000)
}