const allView = document.querySelectorAll('.view')
const allEdit = document.querySelectorAll('.edit')
const allDelete = document.querySelectorAll('.delete-user')
const modalView = document.querySelector('#view')
const modalEdit = document.querySelector('#edit')







allView.forEach(view => {
    view.addEventListener('click', (event) => {   
    
        td = event.target.parentElement;

        console.log(td)
       
        modalView.querySelector('.modal-first-name').innerHTML =   td.querySelector('.first-name').value
        modalView.querySelector('.modal-last-name').innerHTML = td.querySelector('.last-name').value
        modalView.querySelector('.modal-email').innerHTML = td.querySelector('.email').value
        modalView.querySelector('.modal-phone').innerHTML =  td.querySelector('.phone').value

   
        

       
      new bootstrap.Modal(document.getElementById('view')).show()

      
           
       
    })
})

allEdit.forEach(edit => {
    edit.addEventListener('click', (event) => {
       
        
        td = event.target.parentElement;
       
        modalEdit.querySelector('#first-name').value =   td.querySelector('.first-name').value
        modalEdit.querySelector('#last-name').value =   td.querySelector('.last-name').value
        modalEdit.querySelector('#email').value =   td.querySelector('.email').value
        modalEdit.querySelector('#phone').value =   td.querySelector('.phone').value
        modalEdit.querySelector('#user-id').value =   td.querySelector('.user-id').value
        new bootstrap.Modal(document.getElementById('edit')).show()
       
       
    })
})



allDelete.forEach(del => {
    del.addEventListener('click', (event) => {
        // Retrieve the user ID from a custom data attribute
        const userId = event.target.getAttribute('data-user-id');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Send POST request to delete user
                fetch('delete-user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Deleted!',
                            'The user has been deleted.',
                            'success'
                        ).then(() => {
                            
                            location.reload();
                        })
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the user.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'There was an error processing your request.',
                        'error'
                    );
                });
            }
        });
    });
});



	
new DataTable('#users');