document.addEventListener('DOMContentLoaded', function() {

    const changeUsernameBtns = document.querySelectorAll('#change-username');
    const changeNameBtns = document.querySelectorAll('#change-name');
    const changePasswordBtns = document.querySelectorAll('#change-password');
    const deleteAccountBtns = document.querySelectorAll('#delete-account');
    const headerIcon = document.getElementById('header-icon');

    headerIcon.addEventListener('click',function(){
        const user = document.getElementById('admin-name').textContent;
    });


    changeUsernameBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const newusername = prompt("Enter New Username : ");
            const username = btn.getAttribute('data-username');
    
            const xhr = new XMLHttpRequest();
    
            xhr.open('GET', "process_data.php?admin-action=username&username=" + encodeURIComponent(username) + "&newusername=" + encodeURIComponent(newusername));
    
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Username Changes Sucessfully!");
                } else {
                    console.error('Request failed. Status code: ' + xhr.status);
                }
            };
    
            xhr.send();
        });
    });
    

    changeNameBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const newname = prompt("Enter New Name : ");
            const username = btn.getAttribute('data-username');
    
            const xhr = new XMLHttpRequest();
    
            xhr.open('GET', "process_data.php?admin-action=name&username=" + encodeURIComponent(username) + "&name=" + encodeURIComponent(newname));
    
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Name Changed Sucessfully");
                } else {
                    console.error('Request failed. Status code: ' + xhr.status);
                }
            };
    
            xhr.send();
        });
    });

    changePasswordBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const password = prompt("Enter New Password : ");
            const username = btn.getAttribute('data-username');
    
            const xhr = new XMLHttpRequest();
    
            xhr.open('GET', "process_data.php?admin-action=password&username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password));
    
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Password Changed Sucessfully!");
                } else {
                    console.error('Request failed. Status code: ' + xhr.status);
                }
            };
    
            xhr.send();
        });
    });

    deleteAccountBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const username = btn.getAttribute('data-username');
            const confirmation = confirm("Are you sure you want to proceed?");

            if (confirmation) {
                const xhr = new XMLHttpRequest();
    
                xhr.open('GET', "process_data.php?admin-action=delete&username=" + encodeURIComponent(username));
        
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Account Deleted Sucessfully!");
                    } else {
                        console.error('Account Deleted!' + xhr.status);
                    }
                };
        
                xhr.send();
            } else {
            }
        });
    });
});
