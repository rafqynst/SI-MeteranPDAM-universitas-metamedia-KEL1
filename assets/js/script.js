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
function bukaModal(id,nama){

    const modal =
    document.getElementById('modalHapus');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById(
        'namaPelanggan'
    ).innerHTML =
    "Apakah Anda yakin ingin menghapus <b>" +
    nama +
    "</b>?";

    document.getElementById(
        'btnHapus'
    ).href =
    "pelanggan.php?hapus=" + id;
}

function tutupModal(){

    const modal =
    document.getElementById('modalHapus');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}