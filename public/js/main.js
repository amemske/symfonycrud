// get the id of the main table container

//check if artcles exists
//add a click event listener,
//that targets the delete button
//give the user a confirm massage
//get the specific delete button id
//make a fetch request to the backend
//with a delete request
//in the promise reload the window

const allArticles = document.getElementById('articlestbl');
if (allArticles) {
  allArticles.addEventListener('click', (e) => {
    if (e.target.className == 'btn btn-danger delete-article') {
      alert('yes');
      if (confirm('Are you sure?')) {
        //we are passing the id to the server
        const id = e.target.getAttribute('data-id');
        //the fetch is just to display the deleted items in the server
        fetch(`/article/delete/${id}`, {
          method: 'DELETE',
        }).then((res) => window.location.reload); //reload the page
      }
    }
  });
}
