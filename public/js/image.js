window.onload = () => {
    let linksDelete = document.querySelectorAll('[data-delete]')
    console.log(linksDelete);


    for (link of linksDelete) {
        link.addEventListener('click', function (evt) {
            evt.preventDefault();
            if (confirm('Are you sure you want to delete this item?')) {
                let url = this.getAttribute("href");
                let token = this.dataset.token;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ "_token": token })
                }).then(
                    response => response.json()

                ).then(data => {
                    (data.success) ? this.parentElement.remove() : alert(data.error);
                }).catch(e => alert(e))
            }
        });
    }

}


