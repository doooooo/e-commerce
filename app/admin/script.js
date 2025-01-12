
function onDeleteProduct(event)
{
    if(!confirm('Are you sure you want to delete product?'))
    {
        event.preventDefault();
    }
}

function onEditProduct(id,event)
{
    event.preventDefault(); // Prevent any default link behavior

    // Open the popup dialog
    const editDialog = document.getElementById('editDialog');
    editDialog.showModal();

    // Fetch product data using AJAX to populate the form
    fetch(`get_product.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            // Fill the form fields with the product data
            document.querySelector('[name="id"]').value = id;
            document.querySelector('[name="name"]').value = data.NAME;
            document.querySelector('[name="category"]').value = data.Category;
            document.querySelector('[name="price"]').value = data.Price;
        })
        .catch(error => {
            console.error('Error fetching product data:', error);
        });
}

function closePopup(e)
{
    e.preventDefault();
    const editDialog = document.getElementById('editDialog');
    editDialog.close();    
}

function hideSidebar()
{
    const sidebar=document.getElementById("sidebar");
    sidebar.classList.toggle("hide-sidebar");
}