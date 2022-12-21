document.addEventListener('DOMContentLoaded',()=> {
    fetch("journal-search/?category=book")
    .then(response => {
        return response.json();
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.log("エラー");
    });
});