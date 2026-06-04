function togglePassword() {

    const password = document.getElementById("password");

    if(password.type === "password"){
        password.type = "text";
    }else{
        password.type = "password";
    }
}

function toggleSidebar(){

    const sidebar =
    document.getElementById('sidebar');

    sidebar.classList.toggle('show');
}
function konfirmasiHapus(nama){
    return confirm(
        "Yakin ingin menghapus pelanggan " +
        nama +
        " ?"
    );
}